@php
use App\Models\SmtpSetting;
$smtp = SmtpSetting::first();
@endphp

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .content {
            margin: 30px 0;
            line-height: 1.8;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .signature {
            border-top: 1px solid #ddd;
            padding-top: 20px;
            margin-top: 30px;
            white-space: pre-wrap;
            color: #666;
        }

        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- Header --}}
        @if ($smtp && $smtp->mail_header)
        <div class="header">
            {!! $smtp->mail_header !!}
        </div>
        @else
        <div class="header">
            <h2 style="color: #007bff; margin: 0;">Quản Lý Sự Kiện</h2>
        </div>
        @endif

        {{-- Content --}}
        <div class="content">
            <p>Xin chào <strong>{{ $user->ho_ten }}</strong>,</p>

            <p>Cảm ơn bạn đã đăng ký tài khoản. Vui lòng nhấp vào nút bên dưới để xác thực email của bạn.</p>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">Xác thực Email</a>
            </div>

            <p style="color: #666; font-size: 13px;">
                <strong>Lưu ý:</strong> Liên kết này sẽ hết hạn sau 60 phút. Nếu bạn không tạo tài khoản này, hãy bỏ qua email này.
            </p>

            @if ($smtp && $smtp->mail_body_template)
            <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">
            <div style="background-color: #f9f9f9; padding: 15px; border-radius: 4px;">
                {!! nl2br(e($smtp->mail_body_template)) !!}
            </div>
            @endif
        </div>

        {{-- Signature --}}
        @if ($smtp && $smtp->mail_signature)
        <div class="signature">
            {!! nl2br(e($smtp->mail_signature)) !!}
        </div>
        @else
        <div class="signature">
            Trân trọng,<br>
            Đội ngũ Quản Lý Sự Kiện
        </div>
        @endif

        {{-- Footer --}}
        @if ($smtp && $smtp->mail_footer)
        <div class="footer">
            {!! $smtp->mail_footer !!}
        </div>
        @else
        <div class="footer">
            © 2026 Khoa Công Nghệ Thông Tin. Tất cả quyền được bảo lưu.
        </div>
        @endif
    </div>
</body>

</html>