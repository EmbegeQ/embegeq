<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'EmbegeQ' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-zinc-50 text-zinc-900 antialiased">
    <main class="mx-auto flex min-h-screen max-w-3xl flex-col items-center justify-center px-6 py-16">
        <h1 class="text-4xl font-semibold tracking-tight">{{ $title ?? 'EmbegeQ' }}</h1>
        <p class="mt-4 text-center text-zinc-600">
            Stateful PHP framework with Blade-inspired views and Vite + Tailwind CSS 4.
        </p>
    </main>
</body>
</html>
