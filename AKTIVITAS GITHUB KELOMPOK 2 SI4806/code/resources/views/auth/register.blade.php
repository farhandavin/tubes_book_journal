<x-guest-layout>
    <br><br>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card-group mb-0">

                    <div class="card p-4">
                        <div class="card-body">
                            <h1>Daftar</h1>
                            <p class="text-muted">Buat akun baru Anda</p>

                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="input-group mb-3">
                                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                    <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                                </div>
                                @error('name')
                                    <small class="text-danger d-block mb-2">{{ $message }}</small>
                                @enderror

                                <div class="input-group mb-3">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                    <small class="text-danger d-block mb-2">{{ $message }}</small>
                                @enderror

                                <div class="input-group mb-3">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                                </div>
                                @error('password')
                                    <small class="text-danger d-block mb-2">{{ $message }}</small>
                                @enderror

                                <div class="input-group mb-4">
                                    <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi Password" required>
                                </div>
                                @error('password_confirmation')
                                    <small class="text-danger d-block mb-2">{{ $message }}</small>
                                @enderror

                                <button type="submit" class="btn btn-success btn-block w-100">Buat Akun</button>
                            </form>
                        </div>
                    </div>

                    <div class="card text-white bg-success py-5 d-md-down-none" style="width:44%">
                        <div class="card-body text-center d-flex flex-column justify-content-center">
                            <div>
                                <h2>Sudah Punya Akun?</h2>
                                <p>Jika Anda sudah memiliki akun, silakan masuk untuk mengakses jurnal buku Anda.</p>
                                <a href="{{ route('login') }}" class="btn btn-light active mt-3">Login Sekarang!</a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>