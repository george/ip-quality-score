<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>IP Quality Score</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fira-code:400" rel="stylesheet" />

    @vite('./resources/css/app.css')
</head>
<body class="antialiased">
<div class="overflow-none min-h-screen">
    <nav class='p-8 bg-[#121212] text-center text-white text-lg text-green-500 font-semibold'>
        Your IP is <span class='text-red-500'>{{$ip}}</span>. Are you vulnerable?
    </nav>

    <div id="root">
        @vite('./resources/js/app.js')
    </div>
</div>
</body>
</html>
