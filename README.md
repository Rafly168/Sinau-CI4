# Toko Online - Pemrograman Web Lanjut (UAS)

Aplikasi toko online sederhana yang dikembangkan menggunakan framework CodeIgniter 4 untuk mata kuliah Pemrograman Web Lanjut. Proyek ini mencakup fitur-fitur dasar e-commerce seperti manajemen produk, keranjang belanja, proses checkout, autentikasi user, dan manajemen diskon.

## Fitur

* **Autentikasi User:**
    * Sistem login dan logout untuk user dan admin.
    * Pembatasan akses berdasarkan role (admin dapat mengakses manajemen diskon).
* **Manajemen Produk:**
    * Menampilkan daftar produk yang tersedia.
    * Detail produk.
* **Keranjang Belanja:**
    * Menambah, mengurangi, dan menghapus produk dari keranjang.
    * Perhitungan subtotal.
    * **Fitur Baru:** Penerapan diskon pada harga produk di keranjang belanja, jika ada diskon yang aktif.
* **Proses Checkout:**
    * Pengisian data alamat pengiriman.
    * Integrasi dengan webservice untuk pemilihan kelurahan dan jenis layanan pengiriman (estimasi ongkir).
    * **Fitur Baru:** Penerapan diskon pada total harga produk saat menyimpan transaksi ke database.
* **Manajemen Diskon:**
    * Tabel `diskon` di database untuk menyimpan informasi diskon harian.
    * Sistem migrasi dan seeder untuk pengelolaan data diskon.
    * Pencarian diskon otomatis saat user login.
    * Notifikasi diskon aktif di header website.
    * Halaman manajemen diskon khusus untuk role admin (CRUD: Create, Read, Update, Delete).
    * Validasi tanggal unik saat menambah diskon baru.
    * Input tanggal `readonly` pada form edit diskon.
* **Dashboard Transaksi:**
    * Aplikasi dashboard sederhana terpisah yang terintegrasi dengan webservice Toko.
    * Menampilkan daftar transaksi pembelian.
    * **Fitur Baru:** Menampilkan jumlah total item yang dibeli untuk setiap transaksi di dashboard.
    * Deployable dalam folder `public` proyek utama.

## Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek ini:

1.  **Clone Repository:**
    ```bash
    git clone [https://github.com/Rafly168/Sinau-CI4.git](https://github.com/Rafly168/Sinau-CI4.git)
    cd Sinau-CI4
    ```

2.  **Instal Composer Dependencies:**
    ```bash
    composer install
    ```

3.  **Konfigurasi Environment (.env):**
    * Salin file `env` menjadi `.env`:
        ```bash
        cp .env.example .env
        ```
    * Buka file `.env` dan atur konfigurasi database Anda (nama database, username, password). Contoh:
        ```env
        database.default.hostname = localhost
        database.default.database = nama_database_toko_anda
        database.default.username = root
        database.default.password = 
        database.default.DBDriver = MySQLi
        ```
    * Generate `app.baseURL` (sesuaikan dengan URL proyek Anda):
        ```env
        app.baseURL = 'http://localhost:8080/'
        ```
    * Generate `app.encryption.key` (opsional, tapi direkomendasikan):
        ```bash
        php spark key:generate
        ```

4.  **Jalankan Migrasi Database:**
    ```bash
    php spark migrate
    ```

5.  **Jalankan Seeder (Untuk data awal dan data diskon):**
    ```bash
    php spark db:seed AllSeeder # Jika Anda memiliki AllSeeder yang memanggil seeder lain
    php spark db:seed DiskonSeeder # Atau jalankan seeder diskon secara spesifik
    ```
    *Pastikan Anda memiliki seeder untuk user admin awal.*

6.  **Siapkan Dashboard Sederhana :**
    * Pastikan project Dashboard terpisah Anda sudah dikembangkan dan berfungsi.
    * Salin folder project Dashboard Anda (misal `dashboard-toko`) ke dalam folder `public` dari project utama ini.
        ```bash
        cp -r /path/to/your/dashboard-toko-project public/dashboard-toko
        ```
        (Sesuaikan `/path/to/your/dashboard-toko-project` dengan lokasi sebenarnya dari folder dashboard Anda).

7.  **Jalankan Aplikasi:**
    ```bash
    php spark serve
    ```
    Aplikasi akan berjalan di `http://localhost:8080/` (atau port lain yang ditentukan).

## Struktur Proyek

Berikut adalah struktur direktori utama proyek ini:
Sinau-CI4/
├── app/
│   ├── Controllers/
│   │   ├── AuthController.php      # Mengelola login/logout user.
│   │   ├── DiskonController.php    # Mengelola CRUD data diskon (Fitur Baru).
│   │   ├── Home.php                # Controller utama.
│   │   └── ... (Controller lain untuk produk, keranjang, dll.)
│   ├── Database/
│   │   ├── Migrations/
│   │   │   └── 2025-XX-XX_AddDiskonTable.php # Migrasi untuk tabel diskon (Fitur Baru).
│   │   └── Seeds/
│   │       ├── DiskonSeeder.php    # Seeder untuk mengisi data diskon (Fitur Baru).
│   │       └── ... (Seeder lain untuk user, produk, dll.)
│   ├── Models/
│   │   ├── DiskonModel.php         # Model untuk interaksi dengan tabel diskon (Fitur Baru).
│   │   ├── UserModel.php           # Model untuk interaksi dengan tabel user.
│   │   └── ... (Model lain untuk produk, transaksi, dll.)
│   ├── Views/
│   │   ├── auth/                   # View terkait autentikasi.
│   │   ├── diskon/                 # View untuk manajemen diskon (index, create, edit) (Fitur Baru).
│   │   ├── layout/                 # File layout utama (header, footer, dll.)
│   │   │   └── header.php          # Header tempat menampilkan info diskon (Modifikasi).
│   │   └── ... (View lain untuk home, produk, keranjang, dll.)
│   └── Config/
│       └── Routes.php              # Definisi rute aplikasi (Rute untuk diskon ditambahkan).
├── public/
│   ├── index.php                   # File entri utama aplikasi.
│   ├── dashboard-toko/             # Folder berisi project dashboard sederhana (Fitur Baru - UAS Soal 4).
│   └── ... (Aset publik seperti CSS, JS, gambar)
├── tests/
├── writable/
├── .env.example
├── .env                        # File konfigurasi lingkungan.
├── composer.json
├── README.md                   # File dokumentasi proyek ini (Modifikasi).
└── ... (File dan folder CodeIgniter lainnya)