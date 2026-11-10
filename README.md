# 🎸 Xdinary Heroes Studio & Equipment Management System


Janji
Saya Sheva deprianty hermawan dengan NIM 2511645 mengerjakan Tugas Praktikum 7 pada Mata Kuliah Desain dan Pemrograman Berorientasi Objek (DPBO) untuk keberkahan-Nya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin

Aplikasi web manajemen studio berbasis **PHP Native dengan pendekatan Object-Oriented Programming (OOP)** dan **MySQL (PDO Database API)**. Sistem ini dirancang untuk mengelola data anggota (*members*), inventaris peralatan dan alat musik (*guitars/gears*), serta pencatatan status operasional studio Xdinary Heroes secara terstruktur dan responsif.

## 📋 Daftar Isi

1. [Fitur Utama](#-fitur-utama)

2. [Struktur Direktori Proyek](#-struktur-direktori-proyek)

3. [Arsitektur & Pola Desain](#-arsitektur--pola-desain)

4. [Skema & Struktur Database](#-skema--struktur-database)

5. [Prasyarat Sistem](#-prasyarat-sistem)

6. [Langkah Instalasi & Pengaturan](#-langkah-instalasi--pengaturan)

7. [Penggunaan Aplikasi](#-penggunaan-aplikasi)

8. [Panduan Troubleshooting & FAQ](#-panduan-troubleshooting--faq)

## 🚀 Fitur Utama

* **Manajemen Members (CRUD Member)**

  * Menampilkan daftar seluruh personil Xdinary Heroes lengkap dengan foto lokal, nama panggung, dan posisinya.

  * Penambahan dan pembaruan data member.

  * Pengunggahan foto profil (*avatar*) langsung ke penyimpanan lokal server (`assets/uploads/members/`).

  * Penghapusan data member secara aman.

* **Manajemen Inventaris Alat Musik (CRUD Guitars & Gear)**

  * Pengelolaan instrumen (Keyboard, Gitar Elektrik, Bass, Drum, dll.).

  * Kategorisasi instrumen terintegrasi via *Relational Foreign Key*.

  * Manajemen status inventaris (`Available`, `In Use`, `Maintenance`).

  * Pengungahan foto instrumen ke direktori lokal (`assets/uploads/guitars/`).

* **Sistem Keamanan & Utilitas**

  * Manajemen koneksi database aman menggunakan **PDO (PHP Data Objects)** dengan *Prepared Statements* untuk mencegah serangan *SQL Injection*.

  * Notifikasi dinamis (*Alert Handler*) untuk memberikan respon umpan balik bagi aksi user (Create/Update/Delete).

  * Integrasi visual menggunakan path absolut web server untuk mencegah terjadinya *broken image*.

## 📂 Struktur Direktori Proyek

```
TP7DPBO2526C1/
└── program/
    ├── assets/
    │   ├── images/                 # Asset gambar statis/fallback (default-avatar.png, logo.png)
    │   └── uploads/                # Direktori penyimpanan unggahan file lokal
    │       ├── members/            # File foto anggota band (gunil.jpg, jungsu.jpg, dst.)
    │       └── guitars/            # File foto instrumen & peralatan
    ├── class/
    │   ├── Member.php              # Class Model & Logika Query CRUD Member
    │   ├── Guitar.php              # Class Model & Logika Query CRUD Instrumen
    │   └── Loan.php                # Class Model & Logika Query Peminjaman
    ├── config/
    │   └── db.php                  # Konfigurasi Koneksi PDO Database MySQL
    ├── database/
    │   └── db_xdinary_guitar_loan.sql # Skrip Skema SQL & Data Seeder
    ├── helpers/
    │   ├── AlertHandler.php        # Helper untuk Manajemen Flash Session Notification
    │   └── functions.php           # Helper fungsi utilitas umum
    └── view/
        ├── components/             # Komponen UI Reusable (alert.php, navbar.php)
        ├── layouts/                # Layout Master (header.php, footer.php)
        ├── members/                # Antarmuka CRUD Member (index.php, create.php, edit.php)
        ├── guitars/                # Antarmuka CRUD Instrumen (index.php, create.php, edit.php)
        └── loans/                  # Antarmuka Manajemen Peminjaman (index.php, create.php)

```

## 🏛️ Arsitektur & Pola Desain

Aplikasi ini dibangun menggunakan arsitektur berorientasi objek (**OOP**) modular sederhana:

1. **Model/Class Layer (`program/class/`)**: Mengenkapsulasi logika manipulasi data dan interaksi database SQL menggunakan PDO. Setiap tabel diwakili oleh satu *Class*.

2. **View Layer (`program/view/`)**: Bertanggung jawab atas tampilan UI ke pengguna, memproses request input form, dan memanggil fungsi dari Class Layer.

3. **Configuration & Helper (`program/config/`, `program/helpers/`)**: Menyediakan fungsi gloabl seperti manajemen koneksi PDO dan notifikasi sistem.

## 🗄️ Skema & Struktur Database

Database Name: **`db_xdinary_guitar_loan`**

### 1. Tabel `categories`

Menyimpan kategori jenis instrumen.

```
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```

### 2. Tabel `members`

Menyimpan data anggota band.

```
CREATE TABLE IF NOT EXISTS `members` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `stage_name` VARCHAR(50) NOT NULL,
  `position` VARCHAR(100) NOT NULL,
  `photo` VARCHAR(255) DEFAULT 'default-avatar.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```

### 3. Tabel `guitars`

Menyimpan data inventaris alat musik.

```
CREATE TABLE IF NOT EXISTS `guitars` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT DEFAULT NULL,
  `brand` VARCHAR(100) NOT NULL,
  `model` VARCHAR(100) NOT NULL,
  `serial_number` VARCHAR(100) NOT NULL,
  `status` ENUM('Available', 'In Use', 'Maintenance') DEFAULT 'Available',
  `photo` VARCHAR(255) DEFAULT 'default-guitar.png',
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

```

## ⚙️ Prasyarat Sistem

* **Web Server**: Apache (XAMPP / WAMP / Laragon)

* **PHP Version**: Minimum PHP 7.4 atau PHP 8.x

* **Database Server**: MySQL / MariaDB

* **Browser**: Google Chrome, Mozilla Firefox, Microsoft Edge, atau Opera versi terbaru.

## 🛠️ Langkah Instalasi & Pengaturan

### Step 1: Lokasi Direktori Proyek

Pastikan proyek diletakkan tepat pada struktur path berikut di dalam direktori `htdocs` XAMPP:

```
C:\xampp\htdocs\TP7DPBO2526C1\program\

```

### Step 2: Konfigurasi Folder Uploads

Pastikan folder penampung file unggahan foto tersedia dan memiliki penamaan plural (menggunakan akhiran **`s`**):

* `C:\xampp\htdocs\TP7DPBO2526C1\program\assets\uploads\members\`

* `C:\xampp\htdocs\TP7DPBO2526C1\program\assets\uploads\guitars\`

### Step 3: Pengaturan Database di phpMyAdmin

1. Jalankan **Apache** dan **MySQL** dari XAMPP Control Panel.

2. Buka browser dan akses **`http://localhost/phpmyadmin`**.

3. Buat database baru dengan nama **`db_xdinary_guitar_loan`**.

4. Pilih database tersebut, buka tab **SQL**, kemudian eksekusi skrip SQL di bawah ini untuk membuat tabel beserta data awal (*seeder*):

```
-- 1. Buat Tabel Categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Buat Tabel Members
CREATE TABLE IF NOT EXISTS `members` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `stage_name` VARCHAR(50) NOT NULL,
  `position` VARCHAR(100) NOT NULL,
  `photo` VARCHAR(255) DEFAULT 'default-avatar.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Buat Tabel Guitars
CREATE TABLE IF NOT EXISTS `guitars` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT DEFAULT NULL,
  `brand` VARCHAR(100) NOT NULL,
  `model` VARCHAR(100) NOT NULL,
  `serial_number` VARCHAR(100) NOT NULL,
  `status` ENUM('Available', 'In Use', 'Maintenance') DEFAULT 'Available',
  `photo` VARCHAR(255) DEFAULT 'default-guitar.png',
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. Seed Data Categories
INSERT INTO `categories` (`id`, `name`) VALUES 
(1, 'Keyboards & Synths'),
(2, 'Electric Guitars'),
(3, 'Bass Guitars'),
(4, 'Drums & Percussion');

-- 5. Seed Data Members
INSERT INTO `members` (`id`, `name`, `stage_name`, `position`, `photo`) VALUES
(1, 'Goo Gun-il', 'Gun-il', 'Leader, Drummer', 'gunil.jpg'),
(2, 'Kim Jung-su', 'Jungsu', 'Main Vocalist, Keyboardist', 'jungsu.jpg'),
(3, 'Kwak Ji-seok', 'Gaon', 'Lead Rapper, Rhythm Guitarist', 'gaon.jpg'),
(4, 'O Seung-min', 'O.de', 'Rapper, Synthesizer', 'ode.jpg'),
(5, 'Han Hyeong-jun', 'Jun Han', 'Lead Guitarist', 'junhan.jpg'),
(6, 'Lee Joo-yeon', 'Jooyeon', 'Main Vocalist, Bassist', 'jooyeon.jpg');

-- 6. Seed Data Guitars & Equipment
INSERT INTO `guitars` (`category_id`, `brand`, `model`, `serial_number`, `status`, `photo`) VALUES
(1, 'Korg', 'Kronos 2 61-Key (Jungsu)', 'KORG-JS-2024', 'Available', 'korg-kronos.jpg'),
(1, 'Nord', 'Stage 3 73 Compact (O.de)', 'NORD-ODE-99', 'Available', 'nord-stage3.jpg'),
(2, 'Fender', 'Player II Stratocaster (Gaon)', 'FND-GAON-01', 'In Use', 'fender-strat.jpg'),
(2, 'Ibanez', 'RG Series Custom (Jun Han)', 'IBZ-JH-2026', 'Available', 'ibanez-rg.jpg'),
(3, 'Sire', 'Marcus Miller V7 Bass (Jooyeon)', 'SIRE-JY-06', 'In Use', 'sire-v7.jpg'),
(4, 'Pearl', 'Masters Maple Complete Drum (Gun-il)', 'PRL-GUN-01', 'Available', 'pearl-drum.jpg');

```

## 💻 Penggunaan Aplikasi

Buka browser web dan navigasi ke URL berikut:

* **Manajemen Members**:
  `http://localhost/TP7DPBO2526C1/program/view/members/index.php`

* **Inventaris Alat Musik / Gear**:
  `http://localhost/TP7DPBO2526C1/program/view/guitars/index.php`

* **Edit Member (Contoh ID 1)**:
  `http://localhost/TP7DPBO2526C1/program/view/members/edit.php?id=1`

## ❓ Panduan Troubleshooting & FAQ

### 1. Gambar Pecah / Broken Image pada Tabel

* **Penyebab**: Nama folder fisik di Komputer tidak sesuai (`upload/member` alih-alih `uploads/members`) atau file fisik foto belum diletakkan di direktori tersebut.

* **Solusi**:

  1. Pastikan nama folder di komputer sesuai: `program/assets/uploads/members/`.

  2. Pastikan file foto fisik (`gunil.jpg`, `jungsu.jpg`, `gaon.jpg`, `ode.jpg`, `junhan.jpg`, `jooyeon.jpg`) berada di dalam folder tersebut.

  3. Lakukan **Hard Refresh** di browser dengan menekan **`Ctrl + F5`**.

### 2. Error `Column not found: 1054 Unknown column 'photo'`

* **Penyebab**: Kolom `photo` belum ditambahkan ke tabel `members` atau `guitars`.

* **Solusi**: Jalankan perintah berikut di tab SQL phpMyAdmin:

  ```
  ALTER TABLE members ADD COLUMN photo VARCHAR(255) DEFAULT 'default-avatar.png';
  ALTER TABLE guitars ADD COLUMN photo VARCHAR(255) DEFAULT 'default-guitar.png';
  
  ```

### 3. Error `Call to undefined function move_uploaded_path()`

* **Penyebab**: Typo nama fungsi bawaan PHP.

* **Solusi**: Pastikan pada file `edit.php` / `create.php` menggunakan fungsi baku PHP: **`move_uploaded_file($tmp_path, $destination_path)`**.
