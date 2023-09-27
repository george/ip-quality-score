<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IP Quality Score</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=montserrat:900|oxygen:700" rel="stylesheet" />

    @vite('./resources/css/app.css')
</head>
<body class="antialiased">
<div class="overflow-none min-h-screen">
    <nav class='p-6 text-center text-slate-750 bg-white text-xl font-extrabold shadow-xl shadow-gray-200 z-20 mb-10'>
        <span class="text-center">
            Your IP is <span class='text-red-500'>{{$ip}}</span>. Are you vulnerable?
        </span>
    </nav>

    <div id="root">
        @vite('./resources/js/app.js')
    </div>
</div>
</body>
</html>
