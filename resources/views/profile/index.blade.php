@extends('layouts.app')

@section('title', 'Hồ sơ cá nhân')

@section('styles')
    <style>
        .profile-shell {
            display: grid;
            grid-template-columns: minmax(280px, 340px) minmax(0, 1fr);
            gap: 24px;
            align-items: start;
        }

        .profile-panel {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.05);
        }

        .profile-hero {
            position: relative;
            padding: 28px;
            color: #fff;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.18), transparent 34%),
                linear-gradient(145deg, #315184 0%, #243a60 52%, #15233c 100%);
        }

        .profile-hero::after {
            content: '';
            position: absolute;
            inset: auto -40px -60px auto;
            width: 180px;
            height: 180px;
            border-radius: 36px;
            transform: rotate(-18deg);
            background: rgba(255, 255, 255, 0.08);
        }

        .profile-avatar {
            width: 108px;
            height: 108px;
            border-radius: 30px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255, 255, 255, 0.22);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 16px 34px rgba(0, 0, 0, 0.18);
            font-family: var(--font-serif);
            font-size: 2.4rem;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            margin: 0;
            color: #fff;
            font-size: 1.55rem;
        }

        .profile-role-line {
            margin-top: 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .profile-role-chip,
        .profile-status-chip {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.12);
            color: rgba(255, 255, 255, 0.92);
        }

        .profile-email {
            margin: 14px 0 0;
            color: rgba(255, 255, 255, 0.76);
            font-size: 0.95rem;
            word-break: break-word;
        }

        .profile-side-body {
            padding: 22px;
            display: grid;
            gap: 18px;
        }

        .profile-side-section {
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border-light);
        }

        .profile-side-section:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .profile-side-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-light);
        }

        .profile-info-list {
            display: grid;
            gap: 12px;
        }

        .profile-info-item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            font-size: 0.9rem;
        }

        .profile-info-item span:first-child {
            color: var(--text-muted);
            min-width: 94px;
        }

        .profile-info-item strong {
            color: var(--text);
            text-align: right;
            font-weight: 600;
        }

        .profile-mini-stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .profile-mini-stat {
            padding: 14px;
            border-radius: 18px;
            border: 1px solid var(--border-light);
            background: linear-gradient(180deg, #ffffff, #f9fbff);
        }

        .profile-mini-stat strong {
            display: block;
            color: var(--accent);
            font-family: var(--font-serif);
            font-size: 1.35rem;
            margin-bottom: 4px;
        }

        .profile-mini-stat span {
            color: var(--text-light);
            font-size: 0.8rem;
            line-height: 1.5;
        }

        .profile-main {
            display: grid;
            gap: 24px;
        }



        .profile-quick-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .profile-quick-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            background: var(--card);
            border: 1px solid var(--border);
            color: var(--text);
            font-size: 0.82rem;
            font-weight: 600;
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.04);
        }

        .profile-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(280px, 0.82fr);
            gap: 24px;
        }

        .profile-card-head {
            padding: 20px 22px 0;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: flex-start;
            flex-wrap: wrap;
        }

        .profile-card-head h3 {
            margin: 0;
            font-size: 1.1rem;
        }

        .profile-card-head p {
            margin: 6px 0 0;
            color: var(--text-light);
            font-size: 0.88rem;
        }

        .profile-card-body {
            padding: 22px;
        }

        .profile-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .profile-form-grid .full-span {
            grid-column: 1 / -1;
        }

        .profile-readonly {
            background: var(--bg-alt) !important;
            color: var(--text-light);
        }

        .profile-helper {
            display: block;
            margin-top: 6px;
            color: var(--text-muted);
            font-size: 0.78rem;
            line-height: 1.5;
        }

        .profile-submit-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .profile-submit-note {
            color: var(--text-light);
            font-size: 0.84rem;
            max-width: 42ch;
            line-height: 1.6;
        }

        .profile-security-list,
        .profile-activity-list {
            display: grid;
            gap: 12px;
        }

        .profile-security-item,
        .profile-activity-item {
            border: 1px solid var(--border-light);
            border-radius: 18px;
            padding: 16px;
            background: #fff;
        }

        .profile-security-item strong,
        .profile-activity-item strong {
            display: block;
            margin-bottom: 4px;
            color: var(--text);
        }

        .profile-security-item p,
        .profile-activity-item p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.86rem;
            line-height: 1.6;
        }

        .profile-activity-meta {
            margin-top: 10px;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .profile-empty {
            padding: 20px;
            border: 1px dashed var(--border);
            border-radius: 18px;
            color: var(--text-light);
            text-align: center;
            background: var(--bg-alt);
            font-size: 0.88rem;
            line-height: 1.6;
        }

        @media (max-width: 980px) {

            .profile-shell,
            .profile-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {

            .profile-form-grid,
            .profile-mini-stats {
                grid-template-columns: 1fr;
            }

            .profile-banner,
            .profile-submit-row {
                align-items: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $roleLabel = $user->vai_tro === 'admin' ? 'Quản trị viên' : 'Sinh viên';
        $statusLabel = match ($user->trang_thai) {
            'hoat_dong' => 'Đang hoạt động',
            'bi_khoa' => 'Đã khóa',
            default => 'Không hoạt động',
        };
        $avatarLetter = mb_strtoupper(mb_substr($user->ho_ten, 0, 1));
    @endphp

    <div class="profile-shell">
        <aside class="profile-panel">
            <div class="profile-hero">
                <div class="profile-avatar">
                    @if($user->duong_dan_anh)
                        <img src="{{ asset('storage/' . $user->duong_dan_anh) }}" alt="{{ $user->ho_ten }}">
                    @else
                        {{ $avatarLetter }}
                    @endif
                </div>

                <h1 class="profile-name">{{ $user->ho_ten }}</h1>

                <div class="profile-role-line">
                    <span class="profile-role-chip">
                        <i class="bi bi-person-badge"></i>
                        {{ $roleLabel }}
                    </span>
                    <span class="profile-status-chip">
                        <i class="bi bi-shield-check"></i>
                        {{ $statusLabel }}
                    </span>
                </div>

                <p class="profile-email">{{ $user->email }}</p>
            </div>

            <div class="profile-side-body">
                <div class="profile-side-section">
                    <div class="profile-side-title">
                        <i class="bi bi-info-circle"></i>
                        Thông tin tài khoản
                    </div>

                    <div class="profile-info-list">
                        <div class="profile-info-item">
                            <span>MSSV</span>
                            <strong>{{ $user->ma_sinh_vien ?: 'Chưa cập nhật' }}</strong>
                        </div>
                        <div class="profile-info-item">
                            <span>Lớp</span>
                            <strong>{{ $user->lop ?: 'Chưa cập nhật' }}</strong>
                        </div>
                        <div class="profile-info-item">
                            <span>Điện thoại</span>
                            <strong>{{ $user->so_dien_thoai ?: 'Chưa cập nhật' }}</strong>
                        </div>
                        <div class="profile-info-item">
                            <span>Email</span>
                            <strong>{{ $user->hasVerifiedEmail() ? 'Đã xác thực' : 'Chưa xác thực' }}</strong>
                        </div>
                    </div>
                </div>

                <div class="profile-side-section">
                    <div class="profile-side-title">
                        <i class="bi bi-graph-up-arrow"></i>
                        Tóm tắt hoạt động
                    </div>

                    <div class="profile-mini-stats">
                        <div class="profile-mini-stat">
                            <strong>{{ (int) ($registrationStats->total ?? 0) }}</strong>
                            <span>Lượt đăng ký sự kiện</span>
                        </div>
                        <div class="profile-mini-stat">
                            <strong>{{ (int) ($registrationStats->attended ?? 0) }}</strong>
                            <span>Sự kiện đã tham gia</span>
                        </div>
                        <div class="profile-mini-stat">
                            <strong>{{ (int) ($registrationStats->upcoming ?? 0) }}</strong>
                            <span>Lịch tham gia sắp tới</span>
                        </div>
                        <div class="profile-mini-stat">
                            <strong>{{ (int) $totalPoints }}</strong>
                            <span>Tổng điểm đã ghi nhận</span>
                        </div>
                    </div>
                </div>
        </aside>

        <section class="profile-main">


            <div class="profile-grid">
                <div class="profile-panel">
                    <div class="profile-card-head">
                        <div>
                            <h3>Chỉnh sửa hồ sơ</h3>
                            <p>Cập nhật thông tin cá nhân, lớp học, số điện thoại và ảnh đại diện của bạn.</p>
                        </div>
                    </div>

                    <div class="profile-card-body">
                        @if($errors->any())
                            <div class="alert alert-error">
                                <i class="bi bi-exclamation-circle"></i>
                                <div>
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="profile-form-grid">
                                <div class="form-group">
                                    <label class="form-label">Mã sinh viên</label>
                                    <input type="text" value="{{ $user->ma_sinh_vien }}" disabled
                                        class="form-control profile-readonly">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Email</label>
                                    <input type="text" value="{{ $user->email }}" disabled
                                        class="form-control profile-readonly">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Họ và tên *</label>
                                    <input type="text" name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" required
                                        class="form-control @error('ho_ten') is-invalid @enderror">
                                    @error('ho_ten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Lớp *</label>
                                    <input type="text" name="lop" value="{{ old('lop', $user->lop) }}" required
                                        class="form-control @error('lop') is-invalid @enderror" placeholder="VD: 64.CNTT-1">
                                    @error('lop')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="so_dien_thoai"
                                        value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}"
                                        class="form-control @error('so_dien_thoai') is-invalid @enderror"
                                        placeholder="VD: 0912345678">
                                    @error('so_dien_thoai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Ảnh đại diện</label>
                                    <input type="file" name="avatar" accept="image/*"
                                        class="form-control @error('avatar') is-invalid @enderror"
                                        style="border-style:dashed;">
                                    <small class="profile-helper">Hỗ trợ JPEG, PNG, JPG, GIF. Kích thước tối đa 5MB.</small>
                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <hr class="section-rule">

                            <div class="profile-card-head" style="padding:0;margin-bottom:18px;">
                                <div>
                                    <h3>Đổi mật khẩu</h3>
                                    <p>Chỉ nhập khi bạn muốn thay đổi mật khẩu hiện tại.</p>
                                </div>
                            </div>

                            <div class="profile-form-grid">
                                <div class="form-group full-span">
                                    <label class="form-label">Mật khẩu hiện tại</label>
                                    <input type="password" name="mat_khau_cu" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Mật khẩu mới</label>
                                    <input type="password" name="mat_khau_moi"
                                        class="form-control @error('mat_khau_moi') is-invalid @enderror">
                                    @error('mat_khau_moi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Xác nhận mật khẩu</label>
                                    <input type="password" name="mat_khau_moi_confirmation" class="form-control">
                                </div>
                            </div>

                            <div class="profile-submit-row">
                                <p class="profile-submit-note">
                                    Mọi thay đổi được lưu trực tiếp vào hồ sơ hiện tại của bạn. Nếu đổi mật khẩu, hãy dùng
                                    mật khẩu mới cho lần đăng nhập tiếp theo.
                                </p>

                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i>
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="profile-main" style="gap:24px;">


                    <div class="profile-panel">
                        <div class="profile-card-head">
                            <div>
                                <h3>Hoạt động gần đây</h3>
                                <p>Một số sự kiện bạn đã đăng ký gần nhất.</p>
                            </div>
                        </div>

                        <div class="profile-card-body">
                            @if($recentRegistrations->isNotEmpty())
                                <div class="profile-activity-list">
                                    @foreach($recentRegistrations as $registration)
                                        <div class="profile-activity-item">
                                            <strong>{{ $registration->suKien->ten_su_kien ?? 'Sự kiện không còn tồn tại' }}</strong>
                                            <p>
                                                Đăng ký lúc
                                                {{ \Carbon\Carbon::parse($registration->thoi_gian_dang_ky)->format('d/m/Y H:i') }}
                                                @if($registration->suKien)
                                                    · {{ $registration->suKien->dia_diem }}
                                                @endif
                                            </p>
                                            <div class="profile-activity-meta">
                                                <span
                                                    class="badge badge-{{ ($registration->trang_thai_tham_gia === 'da_tham_gia') ? 'success' : (($registration->trang_thai_tham_gia === 'huy') ? 'danger' : 'primary') }}">
                                                    {{ $registration->trang_thai_label }}
                                                </span>
                                                @if($registration->suKien?->thoi_gian_bat_dau)
                                                    <span class="badge badge-secondary">
                                                        {{ $registration->suKien->thoi_gian_bat_dau->format('d/m/Y H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    <div style="text-align: center; margin-top: 16px;">
                                        <a href="{{ route('history.index') }}" class="btn btn-outline-primary btn-sm"
                                            style="width: 100%; border-radius: 999px;">
                                            <i class="bi bi-arrow-right-circle"></i> Xem tất cả
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="profile-empty">
                                    Chưa có lịch sử đăng ký nào để hiển thị. Khi bạn tham gia sự kiện, thông tin sẽ xuất hiện
                                    tại đây.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection