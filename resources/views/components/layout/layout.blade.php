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

    <main class="max-w-7xl mx-auto px-6 min-h-[calc(100vh-4rem-20rem)]">
        {{ $slot }}
    </main>

    <x-layout.footer />

    @session('success')
        <div
            x-data="{show: true}"
            x-init="setTimeout(() => show = false, 3000)"
            x-show="show"
            x-transition.opacity.duration.300ms
            class="bg-primary px-4 py-3 absolute bottom-4 right-4 rounded-lg"
        >
            {{$value}}
        </div>
    @endsession
</body>
</html>
