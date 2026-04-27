<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSuKienRequest;
use App\Http\Requests\Admin\UpdateSuKienRequest;
use App\Models\LichSuDiem;
use App\Models\LoaiSuKien;
use App\Models\MauBaiDang;
use App\Models\SuKien;
use App\Models\ThuVienDaPhuongTien;
use App\Models\User;
use App\Services\NotificationService;
use App\Support\EventTemplateSupport;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SuKienController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $query = SuKien::with('loaiSuKien')
            ->where('la_mau_bai_dang', false);

        if ($request->filled('search')) {
            $query->where('ten_su_kien', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('trang_thai')) {
            $status = $request->trang_thai;
            $now = now();

            if ($status === 'huy') {
                $query->where('trang_thai', 'huy');
            } else {
                $query->where('trang_thai', '!=', 'huy');

                if ($status === 'sap_to_chuc') {
                    $query->where('thoi_gian_bat_dau', '>', $now);
                } elseif ($status === 'dang_dien_ra') {
                    $query->where('thoi_gian_bat_dau', '<=', $now)
                        ->where('thoi_gian_ket_thuc', '>=', $now);
                } elseif ($status === 'da_ket_thuc') {
                    $query->where('thoi_gian_ket_thuc', '<', $now);
                }
            }
        }

        if ($request->filled('ma_loai_su_kien')) {
            $query->where('ma_loai_su_kien', $request->ma_loai_su_kien);
        }

        $sortCol = $request->input('sort_col', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $allowedSorts = ['ten_su_kien', 'created_at'];

        if (in_array($sortCol, $allowedSorts, true)) {
            $query->orderBy($sortCol, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $suKien = $query->paginate(10)->withQueryString();
        $loaiSuKien = LoaiSuKien::all();

        return view('admin.su_kien.index', compact('suKien', 'loaiSuKien'));
    }

    public function selectTemplate()
    {
        $templates = MauBaiDang::with('loaiSuKien')
            ->latest()
            ->get()
            ->map(function (MauBaiDang $template) {
                $template->normalized_modules = EventTemplateSupport::normalizeTemplateModules($template->bo_cuc);
                return $template;
            });

        return view('admin.su_kien.select-template', compact('templates'));
    }

    public function create(Request $request)
    {
        if (!$request->filled('template_id') && !$request->boolean('custom')) {
            return redirect()->route('admin.su-kien.select-template');
        }

        $loai = LoaiSuKien::all();
        $selectedTemplate = $request->filled('template_id')
            ? MauBaiDang::findOrFail($request->integer('template_id'))
            : null;

        $moduleCatalog = EventTemplateSupport::moduleCatalog();
        $moduleSchema = $selectedTemplate
            ? EventTemplateSupport::normalizeTemplateModules($selectedTemplate->bo_cuc)
            : EventTemplateSupport::defaultTemplateModules();

        return view('admin.su_kien.create', compact('loai', 'selectedTemplate', 'moduleCatalog', 'moduleSchema'));
    }

    public function store(StoreSuKienRequest $request)
    {
        $data = $request->validated();
        $data['ma_nguoi_tao'] = auth()->id();
        $data['trang_thai'] = 'sap_to_chuc';
        $data['la_mau_bai_dang'] = false;

        $modules = $this->buildEventModulesFromRequest($request);
        $data['bo_cuc'] = $modules;
        $data['anh_su_kien'] = $this->extractPrimaryBannerPath($modules);
        $data['mo_ta_chi_tiet'] = $this->extractPrimaryDescription($modules);
        $data['so_luong_toi_da'] = $data['so_luong_toi_da'] ?? 0;
        $data['diem_cong'] = $data['diem_cong'] ?? 0;

        $suKien = SuKien::create($data);

        try {
            $notificationService = new NotificationService();
            $studentIds = User::where('vai_tro', 'sinh_vien')
                ->where('trang_thai', 'hoat_dong')
                ->pluck('ma_sinh_vien')
                ->toArray();

            if (!empty($studentIds)) {
                $notificationService->createBulkNotification(
                    $studentIds,
                    'Sự kiện mới: ' . $suKien->ten_su_kien,
                    'Có một sự kiện mới ' . $suKien->ten_su_kien . ' sắp diễn ra. Hãy đăng ký tham gia!',
                    'nhac_nho_su_kien',
                    $suKien->ma_su_kien
                );
            }
        } catch (\Throwable $e) {
            \Log::error('Failed to send notification for event: ' . $e->getMessage());
        }

        return redirect()->route('admin.su-kien.index')
            ->with('success', 'Tạo sự kiện thành công theo mẫu đã chọn.');
    }

    public function show($id)
    {
        $suKien = SuKien::with(['loaiSuKien', 'dangKy.nguoiDung'])
            ->where('la_mau_bai_dang', false)
            ->findOrFail($id);

        return view('admin.su_kien.show', compact('suKien'));
    }

    public function edit($id)
    {
        $suKien = SuKien::where('la_mau_bai_dang', false)->findOrFail($id);
        $loai = LoaiSuKien::all();
        $moduleCatalog = EventTemplateSupport::moduleCatalog();
        $moduleSchema = EventTemplateSupport::normalizeTemplateModules($suKien->bo_cuc);

        return view('admin.su_kien.edit', compact('suKien', 'loai', 'moduleCatalog', 'moduleSchema'));
    }

    public function update(UpdateSuKienRequest $request, $id)
    {
        $suKien = SuKien::where('la_mau_bai_dang', false)->findOrFail($id);
        $data = $request->validated();
        $data['trang_thai'] = $request->input('trang_thai', $suKien->trang_thai);

        $modules = $this->buildEventModulesFromRequest($request, $suKien);
        $data['bo_cuc'] = $modules;
        $data['anh_su_kien'] = $this->extractPrimaryBannerPath($modules);
        $data['mo_ta_chi_tiet'] = $this->extractPrimaryDescription($modules);
        $data['so_luong_toi_da'] = $data['so_luong_toi_da'] ?? 0;
        $data['diem_cong'] = $data['diem_cong'] ?? 0;

        $suKien->update($data);

        return redirect()->route('admin.su-kien.index')
            ->with('success', 'Cập nhật sự kiện thành công.');
    }

    protected function buildEventModulesFromRequest(Request $request, ?SuKien $suKien = null): array
    {
        $schema = EventTemplateSupport::normalizeTemplateModules($request->input('module_schema_json'));
        $existing = collect(EventTemplateSupport::normalizeTemplateModules($suKien?->bo_cuc))
            ->keyBy('id');
        $contentInput   = $request->input('module_content', []);
        $bannerFiles    = $request->file('module_banner', []);
        $galleryFiles   = $request->file('module_gallery', []);
        $bannerPaths    = $request->input('module_banner_media_path', []);  // path chọn từ thư viện
        $galleryMediaIds = $request->input('module_gallery_media_ids', []); // IDs ảnh chọn từ thư viện
        $builtModules   = [];

        foreach ($schema as $module) {
            $moduleId = $module['id'];
            $existingContent = Arr::get($existing->get($moduleId, []), 'content', []);
            $postedContent = Arr::get($contentInput, $moduleId, []);

            $module['content'] = match ($module['type']) {
                'banner' => $this->buildBannerContent(
                    $postedContent,
                    $existingContent,
                    $bannerFiles[$moduleId] ?? null,
                    $bannerPaths[$moduleId] ?? null
                ),
                'header' => [
                    'title'    => trim((string) Arr::get($postedContent, 'title',    Arr::get($existingContent, 'title', ''))),
                    'subtitle' => trim((string) Arr::get($postedContent, 'subtitle', Arr::get($existingContent, 'subtitle', ''))),
                    'badge'    => trim((string) Arr::get($postedContent, 'badge',    Arr::get($existingContent, 'badge', ''))),
                ],
                'info' => [
                    'items' => collect(Arr::get($postedContent, 'items', Arr::get($existingContent, 'items', Arr::get($module, 'settings.items', []))))
                        ->filter(fn($item) => array_key_exists($item, EventTemplateSupport::infoFieldCatalog()))
                        ->values()
                        ->all(),
                    'custom_note' => trim((string) Arr::get($postedContent, 'custom_note', Arr::get($existingContent, 'custom_note', ''))),
                ],
                'description' => [
                    'heading' => trim((string) Arr::get($postedContent, 'heading', Arr::get($existingContent, 'heading', Arr::get($module, 'title', '')))),
                    'body'    => trim((string) Arr::get($postedContent, 'body',    Arr::get($existingContent, 'body', ''))),
                ],
                'gallery' => $this->buildGalleryContent(
                    $postedContent,
                    $existingContent,
                    $galleryFiles[$moduleId] ?? [],
                    array_filter((array) ($galleryMediaIds[$moduleId] ?? []))
                ),
                'documents' => $this->buildDocumentsContent(
                    $postedContent,
                    $existingContent
                ),
                default => Arr::get($existing->get($moduleId, []), 'content', []),
            };

            $builtModules[] = $module;
        }

        $this->cleanupRemovedAssets($existing->all(), $builtModules);

        return $builtModules;
    }

    protected function buildBannerContent(array $posted, array $existing, $file, ?string $mediaPath = null): array
    {
        $imagePath   = Arr::get($existing, 'image_path');
        $keepExisting = (bool) Arr::get($posted, 'keep_existing', $imagePath ? 1 : 0);

        if ($file) {
            // Upload file mới ưu tiên hơn chọn từ thư viện
            if ($imagePath && !str_starts_with($imagePath, 'media/') && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $file->store('su_kien/modules/banner', 'public');
        } elseif ($mediaPath) {
            // Chọn ảnh từ thư viện media (dùng lại path, không copy file)
            $imagePath = $mediaPath;
        } elseif (!$keepExisting && $imagePath) {
            if (!str_starts_with($imagePath, 'media/') && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        }

        return [
            'caption'    => trim((string) Arr::get($posted, 'caption', Arr::get($existing, 'caption', ''))),
            'image_path' => $imagePath,
        ];
    }

    protected function buildGalleryContent(array $posted, array $existing, array $files, array $mediaIds = []): array
    {
        $currentImages = collect(Arr::get($existing, 'images', []))->filter()->values()->all();
        $keptImages    = collect(Arr::get($posted, 'existing_images', $currentImages))->filter()->values()->all();
        $removedImages = array_diff($currentImages, $keptImages);

        foreach ($removedImages as $path) {
            // Không xóa file nếu được lấy từ thư viện media (prefix media/)
            if ($path && !str_starts_with($path, 'media/') && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $newImages = collect($files)
            ->filter()
            ->map(fn($file) => $file->store('su_kien/modules/gallery', 'public'))
            ->values()
            ->all();

        // Ảnh chọn từ thư viện: lấy duong_dan_tep theo ma_phuong_tien
        $mediaImages = [];
        if (!empty($mediaIds)) {
            $mediaImages = ThuVienDaPhuongTien::whereIn('ma_phuong_tien', $mediaIds)
                ->where('loai_tep', 'hinh_anh')
                ->pluck('duong_dan_tep')
                ->all();
        }

        return [
            'images' => array_values(array_unique(array_merge($keptImages, $newImages, $mediaImages))),
        ];
    }

    protected function buildDocumentsContent(array $posted, array $existing): array
    {
        // Lấy danh sách ID tài liệu từ form
        $mediaIds = array_filter((array) Arr::get($posted, 'media_ids', []));

        if (empty($mediaIds)) {
            // Giữ lại nội dung cũ nếu không có thay đổi
            return $existing ?: ['items' => []];
        }

        $docs = ThuVienDaPhuongTien::whereIn('ma_phuong_tien', $mediaIds)
            ->whereIn('loai_tep', ['tai_lieu', 'khac'])
            ->get()
            ->map(fn($m) => [
                'ma_phuong_tien' => $m->ma_phuong_tien,
                'ten_tep'        => $m->ten_tep,
                'duong_dan_tep'  => $m->duong_dan_tep,
                'loai_tep'       => $m->loai_tep,
                'kich_thuoc'     => $m->kich_thuoc,
            ])
            ->values()
            ->all();

        return ['items' => $docs];
    }

    protected function cleanupRemovedAssets(array $existingModules, array $newModules): void
    {
        $newIds = collect($newModules)->pluck('id')->all();

        foreach ($existingModules as $module) {
            if (in_array($module['id'] ?? null, $newIds, true)) {
                continue;
            }

            $content = Arr::get($module, 'content', []);

            if (!empty($content['image_path']) && Storage::disk('public')->exists($content['image_path'])) {
                Storage::disk('public')->delete($content['image_path']);
            }

            foreach (Arr::get($content, 'images', []) as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }
    }

    protected function extractPrimaryBannerPath(array $modules): ?string
    {
        foreach ($modules as $module) {
            if (($module['type'] ?? null) !== 'banner') {
                continue;
            }

            $path = Arr::get($module, 'content.image_path');

            if ($path) {
                return $path;
            }
        }

        return null;
    }

    protected function extractPrimaryDescription(array $modules): ?string
    {
        foreach ($modules as $module) {
            if (($module['type'] ?? null) !== 'description') {
                continue;
            }

            $body = trim((string) Arr::get($module, 'content.body'));

            if ($body !== '') {
                return $body;
            }
        }

        foreach ($modules as $module) {
            if (($module['type'] ?? null) !== 'header') {
                continue;
            }

            $subtitle = trim((string) Arr::get($module, 'content.subtitle'));

            if ($subtitle !== '') {
                return $subtitle;
            }
        }

        return null;
    }

    public function destroy($id)
    {
        SuKien::where('la_mau_bai_dang', false)->findOrFail($id)->delete();

        return back()->with('success', 'Đã xóa sự kiện.');
    }

    public function thongKeDiem()
    {
        $tongDiem = DB::table('lich_su_diem')
            ->join('nguoi_dung', 'lich_su_diem.ma_sinh_vien', '=', 'nguoi_dung.ma_sinh_vien')
            ->select('nguoi_dung.ho_ten', 'nguoi_dung.ma_sinh_vien', DB::raw('SUM(lich_su_diem.diem) as tong_diem'))
            ->groupBy('lich_su_diem.ma_sinh_vien', 'nguoi_dung.ho_ten', 'nguoi_dung.ma_sinh_vien')
            ->orderByDesc('tong_diem')
            ->get();

        return view('admin.thong_ke.diem', compact('tongDiem'));
    }

    public function storeLoaiSuKien(Request $request)
    {
        $request->validate([
            'ten_loai' => 'required|max:200|unique:loai_su_kien,ten_loai',
        ]);

        $loai = LoaiSuKien::create([
            'ten_loai' => $request->ten_loai,
            'mo_ta' => $request->mo_ta,
        ]);

        return response()->json([
            'success' => true,
            'loai' => $loai,
        ]);
    }

    public function kiemTraTrungLich(Request $request)
    {
        $dia_diem = $request->input('dia_diem');
        $bat_dau = $request->input('thoi_gian_bat_dau');
        $ket_thuc = $request->input('thoi_gian_ket_thuc');
        $bo_qua_id = $request->input('bo_qua_id');

        if (empty($dia_diem) || empty($bat_dau) || empty($ket_thuc)) {
            return response()->json(['trung' => false]);
        }

        $query = SuKien::where('la_mau_bai_dang', false)
            ->where('dia_diem', $dia_diem)
            ->where(function ($q) use ($bat_dau, $ket_thuc) {
                $q->whereBetween('thoi_gian_bat_dau', [$bat_dau, $ket_thuc])
                    ->orWhereBetween('thoi_gian_ket_thuc', [$bat_dau, $ket_thuc])
                    ->orWhere(function ($q2) use ($bat_dau, $ket_thuc) {
                        $q2->where('thoi_gian_bat_dau', '<=', $bat_dau)
                            ->where('thoi_gian_ket_thuc', '>=', $ket_thuc);
                    });
            })
            ->whereIn('trang_thai', ['sap_to_chuc', 'dang_dien_ra']);

        if ($bo_qua_id) {
            $query->where('ma_su_kien', '!=', $bo_qua_id);
        }

        $suKienTrung = $query->first();

        if ($suKienTrung) {
            return response()->json([
                'trung' => true,
                'thong_bao' => 'Đã có sự kiện "' . $suKienTrung->ten_su_kien . '" diễn ra tại địa điểm này trong khoảng thời gian đã chọn.',
            ]);
        }

        return response()->json(['trung' => false]);
    }

    public function xoaHinhAnh($id)
    {
        $media = ThuVienDaPhuongTien::find($id);

        if (!$media) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy ảnh.'], 404);
        }

        $media->forceDelete();

        return response()->json(['success' => true]);
    }

    public function checkCollision(Request $request)
    {
        $request->validate([
            'thoi_gian_bat_dau' => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after:thoi_gian_bat_dau',
            'dia_diem' => 'required|string',
            'bo_qua_id' => 'nullable|integer',
        ]);

        $batDau = $request->input('thoi_gian_bat_dau');
        $ketThuc = $request->input('thoi_gian_ket_thuc');
        $diaDiem = $request->input('dia_diem');
        $boQuaId = $request->input('bo_qua_id');

        $conflicts = SuKien::where('la_mau_bai_dang', false)
            ->where('dia_diem', 'like', '%' . $diaDiem . '%')
            ->where(function ($query) use ($batDau, $ketThuc) {
                $query->whereBetween('thoi_gian_bat_dau', [$batDau, $ketThuc])
                    ->orWhereBetween('thoi_gian_ket_thuc', [$batDau, $ketThuc])
                    ->orWhere(function ($q) use ($batDau, $ketThuc) {
                        $q->where('thoi_gian_bat_dau', '<=', $batDau)
                            ->where('thoi_gian_ket_thuc', '>=', $ketThuc);
                    });
            })
            ->where('trang_thai', '!=', 'huy')
            ->select('ma_su_kien', 'ten_su_kien', 'thoi_gian_bat_dau', 'thoi_gian_ket_thuc', 'dia_diem')
            ->when($boQuaId, fn($query) => $query->where('ma_su_kien', '!=', $boQuaId))
            ->get();

        return response()->json([
            'has_collision' => count($conflicts) > 0,
            'conflicts' => $conflicts,
            'message' => count($conflicts) > 0
                ? 'Có ' . count($conflicts) . ' sự kiện khác cùng thời gian và địa điểm'
                : 'Không có sự kiện nào trùng lịch',
        ]);
    }
}
