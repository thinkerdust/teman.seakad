<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Password Anda</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
        }
        .container {
            max-width: 576px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }
        .logo {
            text-align: center;
            margin-bottom: 24px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            text-decoration: none;
        }
        h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1e293b;
            margin-top: 0;
            margin-bottom: 16px;
        }
        p {
            font-size: 15px;
            line-height: 24px;
            margin-top: 0;
            margin-bottom: 24px;
            color: #475569;
        }
        .btn-container {
            text-align: center;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            font-weight: 600;
            font-size: 15px;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
        }
        .btn:hover {
            background-color: #4338ca;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #94a3b8;
            margin-top: 32px;
            line-height: 20px;
        }
        .divider {
            border-top: 1px solid #e2e8f0;
            margin: 24px 0;
        }
        .break-all {
            word-break: break-all;
            font-size: 13px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <span class="logo-text">Teman Seakad</span>
        </div>
        
        <div class="card">
            <h1>Halo!</h1>
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Teman Seakad Anda. Silakan klik tombol di bawah ini untuk mereset password Anda:</p>
            
            <div class="btn-container">
                <a href="{{ $resetUrl }}" class="btn" target="_blank">Reset Password</a>
            </div>
            
            <p>Tautan reset password ini akan kedaluwarsa dalam 60 menit.</p>
            <p>Jika Anda tidak meminta reset password, abaikan saja email ini.</p>
            
            <div class="divider"></div>
            
            <p class="break-all">Jika Anda mengalami masalah saat mengklik tombol "Reset Password", salin dan tempel URL di bawah ini ke browser Anda:<br>
            <a href="{{ $resetUrl }}">{{ $resetUrl }}</a></p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Teman Seakad. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
