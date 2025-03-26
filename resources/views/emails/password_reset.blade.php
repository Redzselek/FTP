<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Password Reset</h2>
        </div>
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            
            <p>Your password has been reset. Here is your new password:</p>
            
            <p><strong>{{ $password }}</strong></p>
            
            <p>We recommend changing this password after logging in for security purposes.</p>
            
            <p>If you did not request this password reset, please contact us immediately.</p>
        </div>
        <div class="footer">
            <p>This is an automated message, please do not reply to this email.</p>
            <p>FilmPlatform © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
