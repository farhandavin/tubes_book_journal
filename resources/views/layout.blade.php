<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Baca Pribadi</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <nav class="navbar">

        <a href="{{ route('home') }}" class="navbar-brand">📚 BookJournal</a>

        <div class="nav-links" style="display: flex; align-items: center; gap: 20px;">
            <a href="{{ route('my.books') }}" style="margin-right: 15px;">Peminjaman Saya</a>
            <a href="{{ route('home') }}">Beranda</a>

            @auth
                <a href="{{ route('ai.index') }}">🤖 Rekomendasi AI</a>

                <a href="{{ route('book.add') }}">Tambah Buku</a>


            @else
                <a href="{{ route('login') }}" class="btn btn-secondary"
                    style="padding: 5px 15px; text-decoration: none; color: white;">Log in</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn"
                        style="padding: 5px 15px; text-decoration: none; color: white;">Register</a>
                @endif
            @endauth

            @auth
                <a href="{{ route('home') }}" style="margin-right: 15px;">Home</a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.users') }}" style="margin-right: 15px; color: gold; font-weight: bold;">Admin
                        Panel</a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" style="margin-right: 15px; color: gold; font-weight: bold;">Admin
                        Dashboard</a>
                @endif

                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger"
                        style="padding: 5px 10px; font-size: 0.9rem; margin-left: 10px;">
                        Logout ({{ Auth::user()->name }})
                    </button>
                </form>

            @endauth
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