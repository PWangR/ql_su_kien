<?php

namespace App\Services;

use App\Models\GeminiSetting;
use App\Models\SuKien;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class GeminiChatbotService
{
    protected static array $runtimeCache = [];

    public function ask(string $message, ?User $user = null): string
    {
        if ($localReply = $this->resolveLocalAnswer($message, $user)) {
            return $localReply;
        }

        $setting = GeminiSetting::getActiveConfig();

        if (!$setting) {
            throw new \RuntimeException('Trợ lý AI hiện chưa được cấu hình hoặc đang tắt.');
        }

        if ($this->shouldCacheMessage($message, $user)) {
            $cacheKey = $this->buildCacheKey($setting, $message);

            if (array_key_exists($cacheKey, self::$runtimeCache)) {
                return self::$runtimeCache[$cacheKey];
            }

            $reply = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($setting, $message) {
                return $this->generateReply($setting, $message, null);
            });

            self::$runtimeCache[$cacheKey] = $reply;

            return $reply;
        }

        return $this->generateReply($setting, $message, $user);
    }

    public static function flushRuntimeCache(): void
    {
        self::$runtimeCache = [];
    }

    public function testConnection(string $prompt, ?User $user = null): string
    {
        $setting = GeminiSetting::getOrCreate();

        if (!$setting->hasApiKey()) {
            throw new \RuntimeException('Chưa có Gemini API key để kiểm tra kết nối.');
        }

        return $this->generateReply($setting, $prompt, $user);
    }

    protected function generateReply(GeminiSetting $setting, string $message, ?User $user = null): string
    {
        $payload = [
            'systemInstruction' => [
                'parts' => [
                    ['text' => trim($setting->system_prompt ?: GeminiSetting::defaultSystemPrompt())],
                ],
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $this->buildPrompt($message, $user)],
                    ],
                ],
            ],
            'generationConfig' => [
                'temperature' => (float) $setting->temperature,
                'maxOutputTokens' => (int) $setting->max_output_tokens,
            ],
            'store' => false,
        ];

        $response = Http::acceptJson()
            ->timeout(20)
            ->withHeaders([
                'x-goog-api-key' => $setting->api_key,
                'Content-Type' => 'application/json',
            ])
            ->post(
                sprintf(
                    'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
                    $setting->model
                ),
                $payload
            );

        if ($response->failed()) {
            throw new \RuntimeException(
                $response->json('error.message') ?: 'Gemini API không phản hồi hợp lệ.'
            );
        }

        $text = $this->extractText($response->json());

        if (blank($text)) {
            throw new \RuntimeException('Gemini không trả về nội dung trả lời.');
        }

        return trim($text);
    }

    protected function resolveLocalAnswer(string $message, ?User $user = null): ?string
    {
        $normalized = $this->normalizeMessage($message);

        return match (true) {
            $user && $this->isUserPointIntent($normalized) => $this->answerUserPoints($user),
            $user && $this->isUserRegistrationIntent($normalized) => $this->answerUserRegistrations($user),
            $this->isUpcomingEventIntent($normalized) => $this->answerUpcomingEvents(),
            $this->isOngoingLocationIntent($normalized) => $this->answerOngoingLocations(),
            $this->isEventCountIntent($normalized) => $this->answerEventSummary(),
            $this->isRegistrationGuideIntent($normalized) => $this->answerRegistrationGuide(),
            $this->isCancelGuideIntent($normalized) => $this->answerCancelRegistrationGuide(),
            $this->isPointPolicyIntent($normalized) => $this->answerPointPolicy(),
            default => null,
        };
    }

    protected function answerUpcomingEvents(): string
    {
        $rows = DB::select(
            '
            SELECT sk.ma_su_kien, sk.ten_su_kien, sk.thoi_gian_bat_dau, sk.dia_diem,
                   sk.so_luong_hien_tai, sk.so_luong_toi_da, sk.diem_cong, lsk.ten_loai
            FROM su_kien sk
            LEFT JOIN loai_su_kien lsk ON lsk.ma_loai_su_kien = sk.ma_loai_su_kien
            WHERE sk.deleted_at IS NULL
              AND sk.trang_thai <> ?
              AND sk.thoi_gian_bat_dau >= ?
            ORDER BY sk.thoi_gian_bat_dau ASC
            LIMIT 3
            ',
            ['huy', now()]
        );

        if (!$rows) {
            return 'Hiện chưa có sự kiện sắp diễn ra trong hệ thống.';
        }

        $lines = ['Các sự kiện gần nhất:'];

        foreach ($rows as $row) {
            $lines[] = sprintf(
                '- %s lúc %s tại %s, %s, %d điểm.',
                $row->ten_su_kien,
                $this->formatDateTime($row->thoi_gian_bat_dau),
                $row->dia_diem ?: 'chưa cập nhật địa điểm',
                $this->formatCapacity($row->so_luong_hien_tai, $row->so_luong_toi_da),
                (int) ($row->diem_cong ?? 0)
            );
        }

        return implode("\n", $lines);
    }

    protected function answerOngoingLocations(): string
    {
        $rows = DB::select(
            '
            SELECT ten_su_kien, dia_diem, thoi_gian_bat_dau, thoi_gian_ket_thuc
            FROM su_kien
            WHERE deleted_at IS NULL
              AND trang_thai <> ?
              AND thoi_gian_bat_dau <= ?
              AND thoi_gian_ket_thuc >= ?
            ORDER BY thoi_gian_ket_thuc ASC
            LIMIT 3
            ',
            ['huy', now(), now()]
        );

        if ($rows) {
            $lines = ['Các sự kiện đang diễn ra:'];

            foreach ($rows as $row) {
                $lines[] = sprintf(
                    '- %s tại %s, đến %s.',
                    $row->ten_su_kien,
                    $row->dia_diem ?: 'chưa cập nhật địa điểm',
                    $this->formatDateTime($row->thoi_gian_ket_thuc)
                );
            }

            return implode("\n", $lines);
        }

        $upcoming = DB::select(
            '
            SELECT ten_su_kien, dia_diem, thoi_gian_bat_dau
            FROM su_kien
            WHERE deleted_at IS NULL
              AND trang_thai <> ?
              AND thoi_gian_bat_dau >= ?
            ORDER BY thoi_gian_bat_dau ASC
            LIMIT 2
            ',
            ['huy', now()]
        );

        if (!$upcoming) {
            return 'Hiện chưa có sự kiện đang diễn ra và cũng chưa có lịch sắp tới.';
        }

        $lines = ['Hiện chưa có sự kiện đang diễn ra. Lịch gần nhất:'];

        foreach ($upcoming as $row) {
            $lines[] = sprintf(
                '- %s lúc %s tại %s.',
                $row->ten_su_kien,
                $this->formatDateTime($row->thoi_gian_bat_dau),
                $row->dia_diem ?: 'chưa cập nhật địa điểm'
            );
        }

        return implode("\n", $lines);
    }

    protected function answerUserRegistrations(User $user): string
    {
        $rows = DB::select(
            '
            SELECT sk.ten_su_kien, sk.thoi_gian_bat_dau, dk.trang_thai_tham_gia,
                   COALESCE(dk.created_at, dk.thoi_gian_dang_ky) AS thoi_gian_tao
            FROM dang_ky dk
            INNER JOIN su_kien sk ON sk.ma_su_kien = dk.ma_su_kien
            WHERE dk.deleted_at IS NULL
              AND sk.deleted_at IS NULL
              AND dk.ma_sinh_vien = ?
            ORDER BY COALESCE(dk.created_at, dk.thoi_gian_dang_ky) DESC
            LIMIT 5
            ',
            [$user->getAuthIdentifier()]
        );

        if (!$rows) {
            return 'Bạn chưa có đăng ký sự kiện nào trong hệ thống.';
        }

        $lines = ['5 đăng ký gần nhất của bạn:'];

        foreach ($rows as $row) {
            $lines[] = sprintf(
                '- %s: %s, bắt đầu %s.',
                $row->ten_su_kien,
                $this->registrationStatusLabel($row->trang_thai_tham_gia),
                $this->formatDateTime($row->thoi_gian_bat_dau)
            );
        }

        return implode("\n", $lines);
    }

    protected function answerUserPoints(User $user): string
    {
        $summary = DB::selectOne(
            '
            SELECT COALESCE(SUM(diem), 0) AS tong_diem, COUNT(*) AS so_giao_dich
            FROM lich_su_diem
            WHERE ma_sinh_vien = ?
            ',
            [$user->getAuthIdentifier()]
        );

        $rows = DB::select(
            '
            SELECT lsd.diem, lsd.nguon, lsd.thoi_gian_ghi_nhan, sk.ten_su_kien
            FROM lich_su_diem lsd
            LEFT JOIN dang_ky dk ON dk.ma_dang_ky = lsd.ma_dang_ky
            LEFT JOIN su_kien sk ON sk.ma_su_kien = dk.ma_su_kien AND sk.deleted_at IS NULL
            WHERE lsd.ma_sinh_vien = ?
            ORDER BY lsd.thoi_gian_ghi_nhan DESC
            LIMIT 3
            ',
            [$user->getAuthIdentifier()]
        );

        if ((int) ($summary->so_giao_dich ?? 0) === 0) {
            return 'Bạn chưa có lịch sử điểm trong hệ thống.';
        }

        $lines = [
            sprintf('Bạn hiện có %d điểm từ %d lần ghi nhận gần đây.', (int) $summary->tong_diem, (int) $summary->so_giao_dich),
        ];

        foreach ($rows as $row) {
            $lines[] = sprintf(
                '- %s điểm từ %s%s.',
                (int) $row->diem,
                $row->ten_su_kien ?: $this->pointSourceLabel($row->nguon),
                $row->thoi_gian_ghi_nhan ? ' lúc ' . $this->formatDateTime($row->thoi_gian_ghi_nhan) : ''
            );
        }

        return implode("\n", $lines);
    }

    protected function answerEventSummary(): string
    {
        $row = DB::selectOne(
            '
            SELECT COUNT(*) AS tong_su_kien,
                   SUM(CASE WHEN deleted_at IS NULL AND trang_thai <> ? AND thoi_gian_bat_dau >= ? THEN 1 ELSE 0 END) AS sap_dien_ra,
                   SUM(CASE WHEN deleted_at IS NULL AND trang_thai <> ? AND thoi_gian_bat_dau <= ? AND thoi_gian_ket_thuc >= ? THEN 1 ELSE 0 END) AS dang_dien_ra
            FROM su_kien
            WHERE deleted_at IS NULL
            ',
            ['huy', now(), 'huy', now(), now()]
        );

        return sprintf(
            'Hệ thống hiện có %d sự kiện, trong đó %d sự kiện sắp diễn ra và %d sự kiện đang diễn ra.',
            (int) ($row->tong_su_kien ?? 0),
            (int) ($row->sap_dien_ra ?? 0),
            (int) ($row->dang_dien_ra ?? 0)
        );
    }

    protected function answerRegistrationGuide(): string
    {
        return implode("\n", [
            'Để đăng ký, bạn vào trang Sự kiện, mở chi tiết sự kiện rồi bấm "Đăng ký tham gia".',
            'Hệ thống chỉ cho đăng ký khi sự kiện chưa hủy, chưa bắt đầu, chưa hết chỗ và bạn chưa đăng ký trước đó.',
            'Nếu sự kiện có giới hạn chỗ, số lượng hiển thị trên trang sẽ được cập nhật theo dữ liệu hiện tại.',
        ]);
    }

    protected function answerCancelRegistrationGuide(): string
    {
        return implode("\n", [
            'Bạn có thể hủy tại trang chi tiết sự kiện bằng nút "Hủy đăng ký".',
            'Hệ thống chỉ cho hủy trước thời điểm sự kiện bắt đầu.',
            'Sau khi hủy, chỗ trống của sự kiện sẽ được hoàn lại nếu sự kiện có giới hạn số lượng.',
        ]);
    }

    protected function answerPointPolicy(): string
    {
        $row = DB::selectOne(
            '
            SELECT COUNT(*) AS tong_su_kien,
                   COALESCE(MIN(diem_cong), 0) AS diem_thap_nhat,
                   COALESCE(MAX(diem_cong), 0) AS diem_cao_nhat
            FROM su_kien
            WHERE deleted_at IS NULL
              AND trang_thai <> ?
            ',
            ['huy']
        );

        return sprintf(
            'Điểm cộng chỉ được ghi sau khi bạn tham gia và được xác nhận. Mức điểm sự kiện hiện có trong hệ thống dao động từ %d đến %d điểm trên %d sự kiện chưa hủy.',
            (int) ($row->diem_thap_nhat ?? 0),
            (int) ($row->diem_cao_nhat ?? 0),
            (int) ($row->tong_su_kien ?? 0)
        );
    }

    protected function buildPrompt(string $message, ?User $user = null): string
    {
        $context = $this->buildContext($message, $user);

        return <<<PROMPT
CTX
{$context}

Q: {$this->compactQuestion($message)}

Yêu cầu:
- Trả lời tối đa 4 câu ngắn.
- Chỉ dùng dữ liệu trong CTX.
- Nếu thiếu dữ liệu, nói rõ "Chưa có thông tin trong hệ thống".
PROMPT;
    }

    protected function buildContext(string $message, ?User $user = null): string
    {
        $lines = [];
        $lines[] = 'NOW=' . now()->format('H:i d/m/Y');
        $lines[] = 'RULES=dk_truoc_bat_dau|khong_huy|con_cho|khong_trung;huy_truoc_bat_dau;diem_sau_xac_nhan';

        $events = $this->selectRelevantEventsForPrompt($message);

        if (empty($events)) {
            $lines[] = 'EVENTS=none';
        } else {
            foreach ($events as $event) {
                $lines[] = sprintf(
                    'E#%s=%s|%s|%s|%s|%s|%s/%s|%sđ|%s',
                    $event->ma_su_kien,
                    $event->ten_su_kien,
                    $event->ten_loai ?? 'khac',
                    $this->formatDateTime($event->thoi_gian_bat_dau),
                    $this->formatDateTime($event->thoi_gian_ket_thuc),
                    $event->dia_diem ?: 'chua_ro',
                    (int) $event->so_luong_hien_tai,
                    (int) $event->so_luong_toi_da,
                    (int) $event->diem_cong,
                    $event->trang_thai
                );
            }
        }

        if ($user) {
            $registrations = DB::select(
                '
                SELECT sk.ma_su_kien, sk.ten_su_kien, dk.trang_thai_tham_gia, sk.thoi_gian_bat_dau
                FROM dang_ky dk
                INNER JOIN su_kien sk ON sk.ma_su_kien = dk.ma_su_kien
                WHERE dk.deleted_at IS NULL
                  AND sk.deleted_at IS NULL
                  AND dk.ma_sinh_vien = ?
                ORDER BY COALESCE(dk.created_at, dk.thoi_gian_dang_ky) DESC
                LIMIT 3
                ',
                [$user->getAuthIdentifier()]
            );

            if (empty($registrations)) {
                $lines[] = 'REGS=none';
            } else {
                foreach ($registrations as $registration) {
                    $lines[] = sprintf(
                        'R#%s=%s|%s|%s',
                        $registration->ma_su_kien,
                        $registration->ten_su_kien,
                        $registration->trang_thai_tham_gia,
                        $this->formatDateTime($registration->thoi_gian_bat_dau)
                    );
                }
            }
        }

        return implode("\n", $lines);
    }

    protected function selectRelevantEventsForPrompt(string $message): array
    {
        $keywords = $this->extractKeywords($message);

        $query = SuKien::query()
            ->leftJoin('loai_su_kien', 'loai_su_kien.ma_loai_su_kien', '=', 'su_kien.ma_loai_su_kien')
            ->whereNull('su_kien.deleted_at')
            ->where('su_kien.trang_thai', '!=', 'huy')
            ->where('su_kien.thoi_gian_ket_thuc', '>=', now()->subDay());

        if ($keywords !== []) {
            $query->where(function ($subQuery) use ($keywords) {
                foreach ($keywords as $keyword) {
                    $like = '%' . $keyword . '%';
                    $subQuery->orWhere('su_kien.ten_su_kien', 'like', $like)
                        ->orWhere('su_kien.mo_ta_chi_tiet', 'like', $like)
                        ->orWhere('su_kien.dia_diem', 'like', $like)
                        ->orWhere('loai_su_kien.ten_loai', 'like', $like);
                }
            });
        }

        return $query
            ->orderByRaw('CASE WHEN su_kien.thoi_gian_bat_dau >= ? THEN 0 ELSE 1 END', [now()])
            ->orderBy('su_kien.thoi_gian_bat_dau')
            ->limit($keywords === [] ? 4 : 5)
            ->get([
                'su_kien.ma_su_kien',
                'su_kien.ten_su_kien',
                'su_kien.thoi_gian_bat_dau',
                'su_kien.thoi_gian_ket_thuc',
                'su_kien.dia_diem',
                'su_kien.so_luong_hien_tai',
                'su_kien.so_luong_toi_da',
                'su_kien.diem_cong',
                'su_kien.trang_thai',
                'loai_su_kien.ten_loai',
            ])
            ->all();
    }

    protected function extractText(array $payload): ?string
    {
        $parts = data_get($payload, 'candidates.0.content.parts', []);

        if (!is_array($parts)) {
            return null;
        }

        $texts = collect($parts)
            ->pluck('text')
            ->filter()
            ->implode("\n");

        return $texts ?: null;
    }

    protected function shouldCacheMessage(string $message, ?User $user = null): bool
    {
        if (!$user) {
            return true;
        }

        $normalized = $this->normalizeMessage($message);

        $personalMarkers = [
            'cua toi',
            'cua minh',
            'cua em',
            'da dang ky',
            'lich su',
            'tai khoan',
            'ho so',
            'trang thai cua toi',
            'trang thai dang ky',
            'toi da',
            'toi co',
            'toi muon',
            'giup toi',
            'cho toi',
            'minh da',
            'minh co',
            'em da',
            'em co',
        ];

        foreach ($personalMarkers as $marker) {
            if (Str::contains($normalized, $marker)) {
                return false;
            }
        }

        return true;
    }

    protected function buildCacheKey(GeminiSetting $setting, string $message): string
    {
        $version = $this->buildPublicContextVersion();

        return 'chatbot_faq:' . sha1(implode('|', [
            $setting->model,
            (string) $setting->temperature,
            (string) $setting->max_output_tokens,
            $version,
            $this->normalizeMessage($message),
        ]));
    }

    protected function buildPublicContextVersion(): string
    {
        $events = SuKien::query()
            ->whereNull('deleted_at')
            ->selectRaw('COUNT(*) as aggregate_count, MAX(updated_at) as aggregate_updated_at')
            ->first();

        return ($events->aggregate_count ?? 0) . '|' . ($events->aggregate_updated_at ?? 'none');
    }

    protected function normalizeMessage(string $message): string
    {
        return (string) Str::of($message)
            ->lower()
            ->ascii()
            ->replaceMatches('/[^a-z0-9#]+/', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim();
    }

    protected function compactQuestion(string $message): string
    {
        return (string) Str::of($message)
            ->squish()
            ->limit(220, '');
    }

    protected function extractKeywords(string $message): array
    {
        $stopWords = [
            'co', 'khong', 'la', 'gi', 'nao', 'toi', 'minh', 'em', 'cho', 'voi',
            'duoc', 'khong', 'nhung', 'cac', 'su', 'kien', 'dang', 'sap', 'hay',
            'giup', 'trong', 'he', 'thong', 'bao', 'nhieu', 'the', 'nao', 'o', 'dau',
        ];

        return collect(explode(' ', $this->normalizeMessage($message)))
            ->filter(fn($word) => $word !== '' && !in_array($word, $stopWords, true))
            ->filter(fn($word) => strlen($word) >= 2 || is_numeric($word))
            ->unique()
            ->take(5)
            ->values()
            ->all();
    }

    protected function isUpcomingEventIntent(string $message): bool
    {
        return $this->containsAny($message, [
            'su kien nao sap',
            'su kien sap',
            'sap dien ra',
            'sap toi',
            'sap to chuc',
            'gan toi',
            'su kien toi',
        ]);
    }

    protected function isOngoingLocationIntent(string $message): bool
    {
        return $this->containsAny($message, [
            'dang dien ra o dau',
            'su kien dang dien ra o dau',
            'dia diem su kien',
            'o dau',
        ]) || (
            $this->containsAny($message, ['dang dien ra', 'dia diem'])
            && $this->containsAny($message, ['su kien', 'o dau'])
        );
    }

    protected function isUserRegistrationIntent(string $message): bool
    {
        return $this->containsAny($message, ['dang ky', 'tham gia', 'lich su', 'trang thai'])
            && $this->containsAny($message, ['toi', 'minh', 'em', 'cua toi']);
    }

    protected function isUserPointIntent(string $message): bool
    {
        return $this->containsAny($message, ['diem', 'tong diem'])
            && $this->containsAny($message, ['toi', 'minh', 'em', 'cua toi']);
    }

    protected function isRegistrationGuideIntent(string $message): bool
    {
        return $this->containsAny($message, [
            'cach dang ky',
            'dang ky nhu the nao',
            'lam sao dang ky',
            'huong dan dang ky',
        ]);
    }

    protected function isCancelGuideIntent(string $message): bool
    {
        return $this->containsAny($message, [
            'cach huy dang ky',
            'lam sao huy dang ky',
            'huong dan huy dang ky',
        ]);
    }

    protected function isPointPolicyIntent(string $message): bool
    {
        return $this->containsAny($message, [
            'diem cong',
            'tinh diem',
            'duoc bao nhieu diem',
            'cong diem',
        ]);
    }

    protected function isEventCountIntent(string $message): bool
    {
        return $this->containsAny($message, [
            'bao nhieu su kien',
            'tong so su kien',
            'co may su kien',
        ]);
    }

    protected function containsAny(string $haystack, array $needles): bool
    {
        foreach ($needles as $needle) {
            if (Str::contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    protected function registrationStatusLabel(?string $status): string
    {
        return match ($status) {
            'da_dang_ky' => 'đã đăng ký',
            'da_tham_gia' => 'đã tham gia',
            'vang_mat' => 'vắng mặt',
            'chua_du_dieu_kien' => 'chưa đủ điều kiện',
            'huy' => 'đã hủy',
            default => 'không xác định',
        };
    }

    protected function pointSourceLabel(?string $source): string
    {
        return match ($source) {
            'tham_gia_su_kien' => 'sự kiện tham gia',
            'thuong_them' => 'thưởng thêm',
            'phat_tru' => 'phạt trừ',
            'he_thong' => 'hệ thống',
            default => 'ghi nhận điểm',
        };
    }

    protected function formatCapacity($current, $max): string
    {
        $current = (int) $current;
        $max = (int) $max;

        if ($max <= 0) {
            return 'không giới hạn chỗ';
        }

        return sprintf('%d/%d chỗ', $current, $max);
    }

    protected function formatDateTime($value): string
    {
        if (!$value) {
            return 'chưa cập nhật';
        }

        return Carbon::parse($value)->format('H:i d/m/Y');
    }
}
