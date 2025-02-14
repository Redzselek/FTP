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
            <h2>Biztonsági Értesítés</h2>
        </div>
        <div class="content">
            <p>Kedves {{ $details['name'] }},</p>
            
            <p>{{ $details['message'] }}</p>
            
            <p>Ha nem Ön végezte ezt a műveletet, kérjük, azonnal változtassa meg jelszavát és lépjen kapcsolatba velünk.</p>
            
            <p>Részletek:</p>
            <ul>
                <li>Időpont: {{ $details['time'] }}</li>
                <li>IP cím: {{ $details['ip'] }}</li>
                <li>Böngésző: {{ $details['browser'] }}</li>
            </ul>
        </div>
        <div class="footer">
            <p>Ez egy automatikus értesítés, kérjük, ne válaszoljon erre az e-mailre.</p>
            <p>FilmPlatform © {{ date('Y') }}</p>
        </div>
    </div>
</body>
</html>
