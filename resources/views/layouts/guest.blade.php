<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MSO System') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=barlow:400,500,600,700,800,900|barlow-condensed:400,500,600,700,800,900" rel="stylesheet" />
    
    <style>
        /* Reset total - hapus semua styling default */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }
        
        /* Hanya tampilkan konten utama, tanpa styling tambahan */
        .guest-content {
            width: 100%;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <div class="guest-content">
        {{ $slot }}
    </div>
</body>
</html>