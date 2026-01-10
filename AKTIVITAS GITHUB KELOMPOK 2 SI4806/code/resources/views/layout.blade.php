<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jurnal Baca Pribadi - BookJournal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Tambahan style cepat untuk merapikan navbar */
        .nav-links a { text-decoration: none; color: #333; transition: 0.3s; }
        .nav-links a:hover { color: #4e73df; }
        .admin-link { color: #d9411e !important; font-weight: bold; }
        .user-name { font-weight: 600; color: #4e73df; }
    </style>
</head>
<body>

    <nav class="navbar" style="padding: 1rem 5%; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
        <a href="{{ route('home') }}" class="navbar-brand" style="font-size: 1.5rem; font-weight: bold;">📚 BookJournal</a>

        <div class="nav-links" style="display: flex; align-items: center; gap: 20px;">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('events.index') }}">Agenda & Event</a>

            @auth
                <a href="{{ route('my.books') }}">Pinjaman Saya</a>
                <a href="{{ route('ai.index') }}">🤖 Rekomendasi AI</a>
                <a href="{{ route('book.add') }}">Tambah Buku</a>
                
                <a href="{{ route('profile.edit') }}" class="user-name">👤 {{ Auth::user()->name }}</a>

                @if(auth()->user()->role === 'admin')
                    <div style="border-left: 1px solid #ddd; height: 20px; margin: 0 5px;"></div>
                    <a href="{{ route('admin.dashboard') }}" class="admin-link">Admin Panel</a>
                @endif

                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="button" onclick="handleLogout()" class="btn btn-danger" 
                        style="padding: 5px 12px; font-size: 0.85rem; border-radius: 5px; cursor: pointer; background: #e74a3b; color: white; border: none;">
                        Keluar
                    </button>
                </form>

            @else
                <a href="{{ route('login') }}" style="color: #4e73df;">Masuk</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" style="background: #4e73df; color: white; padding: 5px 15px; border-radius: 5px;">Daftar</a>
                @endif
            @endauth
        </div>
    </nav>

    <main class="container" style="padding: 2rem 5%; min-height: 70vh;">
        @if(session('success'))
            <script>Swal.fire('Berhasil!', "{{ session('success') }}", 'success');</script>
        @endif
        @if(session('error'))
            <script>Swal.fire('Error!', "{{ session('error') }}", 'error');</script>
        @endif

        @yield('content')
    </main>

    <footer style="text-align: center; padding: 30px; margin-top: 50px; border-top: 1px solid #eee; color: #6c757d;">
        <p>&copy; {{ date('Y') }} <strong>BookJournal</strong>. Dibuat dengan ❤️ untuk pembaca.</p>
    </footer>

    <script>
        function handleLogout() {
            Swal.fire({
                title: 'Ingin keluar?',
                text: "Anda harus login kembali untuk mengakses data.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#e74a3b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }
    </script>

</body>
</html>