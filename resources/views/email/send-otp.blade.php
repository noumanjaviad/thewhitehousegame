<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <title>OTP Sent</title>
</head>

<body style="background-color: #1e2654; color: #fff; text-align: center; padding: 50px; font-family: 'Orbitron', sans-serif;">
    <div>
        <div class="logo">
            <img src="{{ asset('/app-assets/images/logo/logo1.png') }}" alt="The White House Game Logo" style="width: 150px; height: auto; margin-bottom: 10px;">
        </div>
        <div class="container" style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #2a335e; border-radius: 10px;">
            <div>
                {{-- <img src="{{ asset('/app-assets/images/logo/logo.svg') }}" alt="The White House Game Logo"> --}}
                <img src="{{ asset('/app-assets/images/logo/lock-1.png') }}" alt="The White House Game Logo" style="width: 150px; height: auto; margin-bottom: 10px;">
        
            </div>
            <h1>OTP <span style="color: red">SENT</span></h1>
            <p>pwdob@icloud.com</p>
            <p>Your One Time Password is:</p>
            <p class="otp" style="font-size: 30px; margin: 20px 0;">{{ $otp }}</p>
            <p style="line-height: 24px; font-size: 10px;">This email may potentially contain confidential information
                exclusively intended for the recipient. If through error you have received this email we kindly request
                you delete it. We kindly request you inform the sender that you have received this unintended message.
                We also kindly request you do not retain or copy this email and do not use any information contained
                within.</p>
        </div>
        <hr style="margin-top: 10px">
        <div class="footer" style="margin-top: 30px; font-size: 12px;">
            <p>The White House Game © 2024. All Rights Reserved. <a href="http://thewhitehousegame.com" style="color: #fff; text-decoration: none;">Sitemap</a></p>
        </div>
    </div>
</body>
</html>

