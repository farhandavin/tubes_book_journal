<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Baca Pribadi</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>

    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">📚 BookJournal</a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('book.add') }}">Tambah Buku</a>
        </div>
    </nav>

    <main class="container">
        @yield('content')
    </main>

    <footer style="text-align: center; padding: 20px; margin-top: 40px; color: #6c757d;">
        <p>&copy; {{ date('Y') }} BookJournal. Dibuat dengan ❤️.</p>
    </footer>

</body>

</html>