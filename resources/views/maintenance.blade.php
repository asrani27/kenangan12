<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Maintenance Modee - KENANGAN</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            background-image:
                radial-gradient(at 0% 0%, hsla(253, 16%, 7%, 1) 0, transparent 50%),
                radial-gradient(at 50% 0%, hsla(225, 39%, 30%, 1) 0, transparent 50%),
                radial-gradient(at 100% 0%, hsla(339, 49%, 30%, 1) 0, transparent 50%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }

        .container {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 600px;
            width: 100%;
        }

        .icon-wrapper {
            width: 120px;
            height: 120px;
            margin: 0 auto 30px;
            position: relative;
        }

        .icon-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(167, 139, 250, 0.2) 0%, rgba(139, 92, 246, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.4);
            }

            50% {
                box-shadow: 0 0 0 20px rgba(139, 92, 246, 0);
            }
        }

        .icon-wrapper i {
            font-size: 50px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .gear {
            position: absolute;
            width: 30px;
            height: 30px;
            color: rgba(167, 139, 250, 0.5);
            animation: rotate 4s linear infinite;
        }

        .gear:nth-child(1) {
            top: -10px;
            right: -10px;
            animation-duration: 3s;
        }

        .gear:nth-child(2) {
            bottom: -10px;
            left: -10px;
            animation-duration: 5s;
            animation-direction: reverse;
        }

        @keyframes rotate {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .subtitle {
            font-size: 1.125rem;
            color: #94a3b8;
            margin-bottom: 20px;
            font-weight: 400;
        }

        .message {
            font-size: 1rem;
            color: #cbd5e1;
            line-height: 1.7;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.05) 0%, rgba(255, 255, 255, 0.02) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
        }

        .info-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 15px 20px;
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 10px;
            margin-bottom: 30px;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .info-box i {
            color: #a78bfa;
            font-size: 1.2rem;
        }

        .info-box span {
            color: #e2e8f0;
            font-size: 0.95rem;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #94a3b8;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .contact-item:hover {
            color: #a78bfa;
        }

        .contact-item i {
            font-size: 1rem;
        }

        .footer {
            color: #64748b;
            font-size: 0.875rem;
        }

        .footer a {
            color: #a78bfa;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: #8b5cf6;
        }

        /* Responsive */
        @media (max-width: 640px) {
            h1 {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .message {
                padding: 15px;
            }

            .contact-info {
                flex-direction: column;
                gap: 10px;
            }
        }

        /* Floating particles animation */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(167, 139, 250, 0.5);
            border-radius: 50%;
            animation: float 15s infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }

            10% {
                opacity: 1;
            }

            90% {
                opacity: 1;
            }

            100% {
                transform: translateY(-100vh) rotate(720deg);
                opacity: 0;
            }
        }
    </style>
</head>

<body>
    <!-- Floating Particles -->
    <div class="particles">
        <div class="particle" style="left: 10%; animation-delay: 0s;"></div>
        <div class="particle" style="left: 20%; animation-delay: 2s;"></div>
        <div class="particle" style="left: 30%; animation-delay: 4s;"></div>
        <div class="particle" style="left: 40%; animation-delay: 1s;"></div>
        <div class="particle" style="left: 50%; animation-delay: 3s;"></div>
        <div class="particle" style="left: 60%; animation-delay: 5s;"></div>
        <div class="particle" style="left: 70%; animation-delay: 2.5s;"></div>
        <div class="particle" style="left: 80%; animation-delay: 4.5s;"></div>
        <div class="particle" style="left: 90%; animation-delay: 1.5s;"></div>
    </div>

    <div class="container">
        <!-- Icon with rotating gears -->
        <div class="icon-wrapper">
            <div class="icon-circle">
                <i class="fas fa-tools"></i>
            </div>
            <i class="fas fa-cog gear"></i>
            <i class="fas fa-cog gear"></i>
        </div>

        <!-- Title -->
        <h1>Sedang Dalam Pengembangan</h1>

        <!-- Subtitle -->
        <p class="subtitle">Kami akan kembali segera</p>

        <!-- Message -->
        <div class="message">
            Mohon maaf atas ketidaknyamanan ini. Sistem sedang dalam proses pemeliharaan dan peningkatan untuk
            memberikan pengalaman yang lebih baik bagi Anda.
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <span>Estimasi: Segera selesai</span>
        </div>

        <!-- Contact Info -->
        <div class="contact-info">
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <span>support@kenangan.com</span>
            </div>
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <span>+62 812 3456 7890</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© 2026 <a href="#">KENANGAN</a>. All rights reserved.</p>
        </div>
    </div>
</body>

</html>