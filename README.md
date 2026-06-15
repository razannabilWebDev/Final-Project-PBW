# 🛒 Groceria - Sistem Informasi Warung Kelontong

![PHP](https://img.shields.io/badge/PHP-Native-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![Composer](https://img.shields.io/badge/Composer-Dependency_Manager-brown)
![PHPMailer](https://img.shields.io/badge/PHPMailer-Email-green)
![Status](https://img.shields.io/badge/Status-Development-yellow)

## 📖 Deskripsi Proyek

**Groceria** merupakan Sistem Informasi Warung Kelontong berbasis web yang dikembangkan untuk membantu proses pengelolaan operasional warung secara lebih modern, efisien, dan terintegrasi.

Aplikasi ini menyediakan fitur manajemen pengguna, pengelolaan barang, supplier, pelanggan, transaksi penjualan, monitoring stok, serta laporan penjualan yang dapat dicetak dalam format PDF.

Proyek ini dikembangkan sebagai tugas kelompok pada mata kuliah **Pemrograman Berbasis Web** di **Universitas Singaperbangsa Karawang**.

---

## 🎯 Tujuan Sistem

* Mempermudah pengelolaan data barang dan stok.
* Membantu proses transaksi penjualan.
* Mengelola data supplier dan pelanggan.
* Menyediakan laporan penjualan secara otomatis.
* Mengurangi pencatatan manual yang rentan terhadap kesalahan.
* Menyediakan dashboard monitoring aktivitas warung secara real-time.

---

## ✨ Fitur Utama

### 🔐 Sistem Autentikasi

* Login menggunakan Username atau Email
* Registrasi Akun
* Logout
* Forgot Password
* Verifikasi OTP melalui Email
* Reset Password

### 👥 Manajemen Pengguna

* Tambah User
* Hapus User
* Role Management

  * Admin
  * Kasir

### 📊 Dashboard

* Total Barang
* Total Stok
* Total Transaksi
* Total User
* Pendapatan Hari Ini
* Total Pendapatan
* Total Pembelian
* Aktivitas Terbaru
* Barang Terbaru

### 📦 Manajemen Inventaris

* Data Barang
* Monitoring Stok
* Status Barang
* Kategori Barang

### 🚚 Manajemen Supplier

* Kelola Data Supplier
* Informasi Kontak Supplier

### 🧑‍🤝‍🧑 Manajemen Pelanggan

* Kelola Data Pelanggan
* Sistem Poin Member

### 🛍️ Transaksi

* Transaksi Penjualan
* Detail Transaksi
* Perhitungan Total Harga
* Pembayaran
* Kembalian Otomatis

### 📈 Laporan

* Laporan Penjualan
* Filter Periode Tanggal
* Statistik Pendapatan
* Grafik Pendapatan
* Cetak Laporan PDF

---

## 🗄️ Struktur Database

Database menggunakan beberapa tabel utama:

```text
user
barang
stok
supplier
pelanggan
transaksi
detail_transaksi
pembelian
detail_pembelian
```

### Relasi Sederhana

```text
User
 │
 ├── Transaksi
 │       │
 │       └── Detail Transaksi
 │                │
 │                └── Barang
 │
 └── Pembelian
         │
         └── Detail Pembelian
                    │
                    └── Barang

Pelanggan ─── Transaksi
Supplier ─── Pembelian
Barang ─── Stok
```

---

## 🛠️ Teknologi yang Digunakan

### Backend

* PHP Native

### Database

* MySQL / MariaDB

### Frontend

* HTML5
* CSS3
* JavaScript

### Library

* PHPMailer

### Dependency Manager

* Composer

---

## 📂 Struktur Proyek

```text
warung-kelontong/
│
├── assets/
├── barang/
├── config/
├── dashboard/
├── database/
├── laporan/
├── pelanggan/
├── supplier/
├── templates/
├── transaksi/
├── user/
├── vendor/
│
├── login.php
├── register.php
├── forgot_password.php
├── reset_password.php
├── verify_otp.php
├── logout.php
└── index.php
```

---

## ⚙️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/razannabilWebDev/Final-Project-PBW
```

### 2. Masuk ke Folder Project

```bash
cd FINAL-PROJECT-PBW
```

### 3. Install Dependency

```bash
composer install
```

### 4. Import Database

Import file:

```text
database/warung_kelontong.sql
```

ke MySQL melalui phpMyAdmin atau MySQL Client.

### 5. Konfigurasi Database

Sesuaikan file:

```text
config/koneksi.php
```

### 6. Konfigurasi Email

Sesuaikan konfigurasi SMTP pada:

```text
config/mailer.php
```

### 7. Jalankan Project

Aktifkan:

* Apache
* MySQL

kemudian akses:

```text
http://localhost/groceria
```

---

## 👨‍💻 Tim Pengembang

### Kelompok Pemrograman Berbasis Web

* Razan Nabil Annadif
* Hanna Fadillah Septiana
* Arsya Apricia Purnomo
* Muhammad Fikri Maulana
* Mustikasari Yahya

### Institusi

Universitas Singaperbangsa Karawang

---

## 📌 Status Pengembangan

🟢 Aktif Dikembangkan

Beberapa modul masih dalam tahap penyempurnaan dan pengembangan fitur lanjutan.

---

## 📄 Lisensi

Proyek ini dikembangkan untuk kebutuhan akademik pada mata kuliah Pemrograman Berbasis Web Universitas Singaperbangsa Karawang.
