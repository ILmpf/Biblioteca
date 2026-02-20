<!doctype html>
<html lang="en" data-theme="lemonade">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biblioteca</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-layout.nav />

    <x-layout.notifications />

    <main class="max-w-7xl mx-auto px-6 min-h-[calc(100vh-4rem-20rem)]">
        {{ $slot }}
    </main>

    <x-layout.footer />
</body>
</html>
