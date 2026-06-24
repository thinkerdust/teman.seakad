<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reminder: Langganan Anda Akan Berakhir</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <span class="logo-text">Teman Seakad</span>
        </div>
        
        <div class="card">
            <h1>Halo, {{ $name }}!</h1>
            <p>Masa aktif langganan Anda akan berakhir dalam <strong>{{ $daysRemaining }} hari</strong> pada tanggal <strong>{{ $endDate }}</strong>.</p>
            <p>Silakan klik tombol di bawah ini untuk memperpanjang langganan agar tetap dapat mengakses seluruh fitur Teman Seakad:</p>
            
            <div class="btn-container">
                <a href="{{ url('/admin') }}" class="btn" target="_blank">Perpanjang Langganan</a>
            </div>
            
            <p>Jika Anda sudah melakukan perpanjangan, abaikan email ini.</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Teman Seakad. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
