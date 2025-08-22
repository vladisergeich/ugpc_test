<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#000000"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="My PWA">
    <link rel="apple-touch-icon" href="/pwa-192x192.png">
    @vite(['resources/js/app.js'])
    @inertiaHead

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
</head>

<body>
@inertia
</body>
</html>
