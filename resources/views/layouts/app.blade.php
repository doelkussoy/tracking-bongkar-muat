<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('tracking.png') }}">
    <title>Tracking Bongkar Muat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- 👇 TAMBAHKAN BLOK INI UNTUK MENGHILANGKAN GARIS PUTIH --}}
    <style>
        body {
            margin: 0 !important;
        }
    </style>
</head>
<body class="h-full">
    {{ $slot }} 
    @livewireScripts
</body>
</html>