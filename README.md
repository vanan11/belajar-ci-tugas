# Toko Online CodeIgniter 4

Proyek ini adalah platform toko online yang dibangun menggunakan [CodeIgniter 4](https://codeigniter.com/). Sistem ini menyediakan fungsionalitas lengkap untuk toko online, mulai dari manajemen produk, keranjang belanja, transaksi dengan integrasi ongkos kirim, hingga sistem diskon harian.

## Daftar Isi

- [Fitur](#fitur)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Struktur Proyek](#struktur-proyek)

## Fitur

- **Katalog Produk**: Menampilkan produk dengan gambar, nama, dan harga.
- **Keranjang Belanja**:
  - Menambah produk ke keranjang.
  - Memperbarui jumlah produk.
  - Menghapus produk dari keranjang.
  - Mengosongkan keranjang.
- **Sistem Transaksi**:
  - Proses checkout yang aman.
  - Integrasi dengan API RajaOngkir untuk perhitungan ongkos kirim secara dinamis.
  - Riwayat transaksi untuk setiap pengguna.
- **Sistem Diskon**:
  - Diskon harian yang otomatis diterapkan saat pengguna login.
  - Harga produk di keranjang dan checkout otomatis dikurangi jika ada diskon.
- **Panel Admin**:
  - Manajemen Produk (CRUD).
  - Manajemen Kategori Produk (CRUD).
  - **Manajemen Diskon (CRUD)** dengan validasi untuk mencegah tanggal duplikat.
- **Sistem Autentikasi**:
  - Login untuk pengguna dengan peran 'admin' dan 'guest'.
  - Filter untuk membatasi akses halaman berdasarkan peran pengguna.
- **Web Service (API)**:
  - Menyediakan endpoint API untuk data transaksi.
  - Endpoint diamankan menggunakan API Key.
- **Dashboard Eksternal**:
  - Aplikasi dashboard sederhana yang menampilkan data transaksi dari Toko Online melalui API.
- **UI Responsif**: Menggunakan template NiceAdmin untuk tampilan yang modern dan responsif.

## Persyaratan Sistem

- PHP versi 8.1 atau lebih tinggi.
- Composer.
- Web server (Contoh: XAMPP, Laragon).
- Koneksi internet (untuk dependensi Composer dan API ongkos kirim).

## Instalasi

1.  **Clone Repository**

    ```bash
    git clone [URL_REPOSITORY_ANDA]
    cd [NAMA_FOLDER_PROYEK]
    ```

2.  **Install Dependensi**
    Pastikan Anda memiliki `composer.json`, lalu jalankan:

    ```bash
    composer install
    ```

3.  **Konfigurasi Environment**
    Salin file `env` menjadi `.env` dan sesuaikan konfigurasinya.

    ```bash
    cp env .env
    ```

    Buka file `.env` dan atur baris berikut:

    ```
    app.baseURL = 'http://localhost:8080'

    database.default.database = 'db_ci4'
    database.default.username = 'root'
    database.default.password = ''

    # API Key untuk RajaOngkir
    COST_KEY = "MASUKKAN_API_KEY_RAJAONGKIR_ANDA"

    # API Key untuk webservice dashboard
    API_KEY = "random123678abcghi"
    ```

4.  **Buat Database**
    Buat sebuah database baru di MySQL / MariaDB dengan nama `db_ci4`.

5.  **Jalankan Migrasi Database**
    Perintah ini akan membuat semua tabel yang dibutuhkan (`users`, `product`, `transaction`, `diskon`, dll).

    ```bash
    php spark migrate
    ```

6.  **Jalankan Seeder Data**
    Perintah ini akan mengisi tabel dengan data awal.

    ```bash
    php spark db:seed UserSeeder
    php spark db:seed ProductCategorySeeder
    php spark db:seed ProductSeeder
    php spark db:seed DiskonSeeder
    ```

7.  **Jalankan Server**

    ```bash
    php spark serve
    ```

8.  **Akses Aplikasi**
    - Buka browser dan akses `http://localhost:8080` untuk Toko Online.
    - Untuk melihat dashboard, akses `http://localhost:8080/dashboard-toko/`.

## Struktur Proyek

Proyek ini mengikuti struktur Model-View-Controller (MVC) dari CodeIgniter 4:

- `app/Controllers` - Berisi logika aplikasi dan penanganan request.
  - `AuthController.php`: Mengelola autentikasi (login/logout).
  - `ProdukController.php`: Manajemen data produk.
  - `DiskonController.php`: Manajemen data diskon.
  - `TransaksiController.php`: Mengelola proses keranjang, checkout, dan integrasi ongkir.
  - `ApiController.php`: Menyediakan data transaksi sebagai webservice.
- `app/Models` - Kelas-kelas model untuk interaksi dengan database.
  - `ProductModel.php`
  - `UserModel.php`
  - `TransactionModel.php`
  - `DiskonModel.php`
- `app/Views` - Berisi file-file template dan komponen UI.
  - `layout.php`: Template utama.
  - `components/`: Potongan UI yang dapat digunakan kembali (header, sidebar).
  - `v_produk.php`, `v_diskon.php`, `v_keranjang.php`, dll.
- `app/Database` - Berisi file migrasi dan seeder.
  - `Migrations/`: Definisi skema tabel database.
  - `Seeds/`: Data awal untuk diisi ke dalam tabel.
- `app/Config/Routes.php` - Tempat mendefinisikan semua rute URL aplikasi.
- `public/` - Folder publik yang dapat diakses web.
  - `img/`: Gambar-gambar produk.
  - `NiceAdmin/`: Aset dari template admin.
  - `dashboard-toko/`: Aplikasi dashboard eksternal.
