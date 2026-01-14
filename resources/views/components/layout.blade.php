<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen" data-theme="coffee">

    {{ $slot }}

    <footer class="relative z-10 text-center py-6 text-sm text-base-content/60">
        © {{ date('Y') }} Biblioteca · Inovcorp . Leonardo Fraqueiro
    </footer>
</body>

</html>
