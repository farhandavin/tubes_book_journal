# 📚 BookJournal - Jurnal Bacaan & Manajemen Pustaka Pribadi

**BookJournal** adalah aplikasi web berbasis **Laravel** yang dirancang untuk membantu pengguna mencatat, mengelola, dan melacak buku-buku yang telah mereka baca. Aplikasi ini dilengkapi dengan integrasi **OpenLibrary API** untuk pencarian data buku otomatis dan fitur **AI Recommendation** untuk memberikan saran bacaan yang cerdas.

-----

## ✨ Fitur Unggulan

Aplikasi ini memiliki berbagai fitur untuk mendukung aktivitas membaca pengguna:

1.  **Manajemen Buku (CRUD Lengkap):**

      * Menambah buku ke koleksi pribadi.
      * Mengedit ulasan, rating, dan tanggal baca.
      * Menghapus buku dari koleksi.
      * Melihat daftar buku dengan opsi pengurutan (Sorting) berdasarkan Judul, Rating, atau Tanggal.

2.  **Integrasi API Publik (OpenLibrary):**

      * Fitur pencarian buku otomatis menggunakan **OpenLibrary API**. Pengguna cukup memasukkan judul, dan sistem akan mengambil metadata (Penulis, ISBN, Cover) secara otomatis.

3.  **Rekomendasi Cerdas (AI Powered):**

      * Fitur khusus yang menggunakan **Artificial Intelligence (Google Gemini)** untuk menganalisis buku-buku favorit pengguna dan memberikan rekomendasi bacaan selanjutnya.

4.  **Autentikasi & Keamanan:**

      * Sistem Login, Register, dan Logout yang aman menggunakan **Laravel Breeze**.
      * Setiap pengguna memiliki data buku yang terisolasi (Private Journal).

5.  **Fitur Ekspor Data:**

      * Pengguna dapat mengunduh laporan jurnal bacaan mereka dalam format **CSV/Excel**.

-----

## 🛠️ Teknologi yang Digunakan

  * **Backend:** PHP 8.2+, Laravel 11/12
  * **Frontend:** Blade Templates, Tailwind CSS (via Vite), Bootstrap 5 (CDN).
  * **Database:** SQLite (Default) / MySQL / PostgreSQL.
  * **API Integrations:** OpenLibrary API, Google Gemini AI.
  * **Packages:** GuzzleHTTP, Laravel Breeze, Laravel Sanctum.

-----

## ⚙️ Persyaratan Sistem (Prerequisites)

Sebelum menjalankan proyek ini, pastikan komputer Anda telah terinstal:

  * [PHP](https://www.php.net/downloads) \>= 8.2
  * [Composer](https://getcomposer.org/)
  * [Node.js & NPM](https://nodejs.org/)
  * Git

-----

## 🚀 Cara Instalasi & Menjalankan (Lokal)

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer Anda:

### 1\. Clone Repository

```bash
git clone https://github.com/username-anda/tubes_book_journal.git
cd tubes_book_journal
```

### 2\. Install Dependensi Backend (PHP)

```bash
composer install
```

### 3\. Install Dependensi Frontend (Node.js)

```bash
npm install
```

### 4\. Konfigurasi Environment (.env)

Salin file konfigurasi contoh dan buat file `.env` baru:

```bash
cp .env.example .env
```

Buka file `.env` dan atur konfigurasi berikut:

  * **Database:** Secara default menggunakan SQLite. Jika ingin menggunakan MySQL, ubah `DB_CONNECTION=mysql` dan sesuaikan nama databasenya.
  * **API Key AI:** Tambahkan baris ini di paling bawah file `.env` untuk mengaktifkan fitur rekomendasi AI:
    ```ini
    GEMINI_API_KEY="masukkan_api_key_google_gemini_anda_disini"
    ```
    *(Anda bisa mendapatkan API Key gratis di Google AI Studio)*.

### 5\. Generate Key Aplikasi

```bash
php artisan key:generate
```

### 6\. Migrasi Database

Buat tabel database yang diperlukan:

```bash
php artisan migrate
```

*(Pilih "Yes" jika ditanya untuk membuat file database.sqlite)*.

### 7\. Jalankan Aplikasi

Anda perlu menjalankan dua terminal terpisah:

**Terminal 1 (Menjalankan Server Laravel):**

```bash
php artisan serve
```

**Terminal 2 (Menjalankan Vite untuk Asset/CSS):**

```bash
npm run dev
```

Akses aplikasi melalui browser di: `http://localhost:8000`

-----

## 📖 Cara Penggunaan

1.  **Registrasi:** Buka halaman Register dan buat akun baru.
2.  **Tambah Buku:**
      * Klik menu "Tambah Buku".
      * Ketik judul buku di kolom pencarian (misal: "Harry Potter") dan klik "Cari".
      * Pilih buku dari hasil pencarian OpenLibrary, isi Rating dan Catatan, lalu Simpan.
3.  **Manajemen:** Lihat buku di Beranda. Anda bisa mengedit atau menghapus buku tersebut.
4.  **Fitur AI:**
      * Pastikan Anda sudah memiliki beberapa buku dengan rating tinggi.
      * Klik menu "🤖 Rekomendasi AI" di Navbar.
      * AI akan memberikan saran buku baru berdasarkan selera Anda.
5.  **Export:** Klik tombol "Export ke Excel/CSV" di halaman Beranda untuk mengunduh data.

-----

## 📂 Struktur Proyek

  * `app/Http/Controllers`: Logika utama aplikasi (BookController, AIController).
  * `app/Models`: Model database (Book, User).
  * `resources/views`: Tampilan antarmuka pengguna (Blade Templates).
  * `routes/web.php`: Definisi jalur URL aplikasi.
  * `database/migrations`: Struktur skema database.

-----

## 🤝 Kontribusi & Lisensi

Proyek ini dibuat untuk memenuhi Tugas Besar Pemrograman Web. Dilarang menyalin tanpa izin untuk keperluan komersial.

**Credits:**

  * Dibuat oleh: kelompok 6 WAD
  * Program Studi Sistem Informasi - Telkom University
