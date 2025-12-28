<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'BookJournal') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">📚 BookJournal</a>

        <div class="nav-links" style="display: flex; align-items: center; gap: 20px;">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('book.add') }}">Tambah Buku</a>

            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 0.9rem;">
                    Logout ({{ Auth::user()->name }})
                </button>
            </form>
        </div>
    </nav>

    <main class="container" style="min-height: 80vh;">
        @isset($header)
            <div style="margin-bottom: 20px; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                {{ $header }}
            </div>
        @endisset

        {{ $slot }}
    </main>

    <footer style="text-align: center; padding: 20px; margin-top: 40px; color: #6c757d;">
        <p>&copy; {{ date('Y') }} BookJournal. Dibuat dengan ❤️.</p>
    </footer>

</body>

</html>