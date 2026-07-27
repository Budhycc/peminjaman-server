# Dokumentasi API Sistem Peminjaman Aset Server

Dokumentasi ini berisi detail spesifikasi API, cara instalasi, serta panduan lengkap cara menguji (testing) API menggunakan Postman.

---

## 1. Persiapan Server & Instalasi Lokal

Jika Anda baru melakukan *clone* proyek ini, ikuti langkah-langkah berikut untuk menjalankannya:

1. Copy file `.env.example` menjadi `.env`.
   ```bash
   cp .env.example .env
   ```
2. Instal dependensi PHP menggunakan Composer.
   ```bash
   composer install
   ```
3. Generate application key.
   ```bash
   php artisan key:generate
   ```
4. Konfigurasi database di file `.env` (secara default menggunakan SQLite).
5. Jalankan migrasi dan *seeder* untuk membuat struktur database dan data *dummy* pengguna.
   ```bash
   php artisan migrate --seed
   ```
6. Jalankan server lokal Laravel.
   ```bash
   php artisan serve
   ```
   *(Secara default, API dapat diakses melalui `http://localhost:8000/api`)*

---

## 2. Cara Pakai (Panduan Menggunakan Postman)

API ini dilindungi oleh **Laravel Sanctum**. Setiap permintaan *endpoint* (kecuali untuk jalur login) harus menyertakan **Bearer Token** pada *Header*. 

Sistem ini menerapkan **Role-Based Access Control (RBAC)** dengan dua peran utama:
- **User:** Hanya dapat melihat daftar aset tersedia, melihat detail aset, melakukan peminjaman, mengembalikan aset, dan melihat riwayat peminjamannya sendiri.
- **Admin:** Memiliki kontrol penuh untuk mengatur pengguna, mengelola data aset, melihat seluruh transaksi peminjaman/pengembalian, serta mengakses log dan laporan.

### A. Pengaturan Global (Headers)
Untuk memastikan Laravel merespons dengan format JSON, selalu sertakan header berikut pada setiap *request* Postman:
- `Accept` : `application/json`

### B. Mendapatkan Bearer Token (Proses Login)
1. Buat *request* baru di Postman.
2. Atur metode menjadi **POST** dan masukkan URL: `http://localhost:8000/api/login`.
3. Buka tab **Body**, pilih **raw**, lalu pilih tipe **JSON**.
4. Masukkan payload berikut (sesuaikan dengan data seeder Anda):
   ```json
   {
       "username": "admin",
       "password": "password"
   }
   ```
5. Klik **Send**. Respons akan mengembalikan `access_token` (contoh: `1|abcde...`). **Copy token ini**.

### C. Menerapkan Token untuk Request Lain
1. Buka *request* baru untuk endpoint lain (misalnya lihat daftar aset).
2. Pindah ke tab **Authorization** di Postman.
3. Pilih tipe **Bearer Token**.
4. *Paste* token yang disalin pada kolom **Token**.

*(Tips Postman: Anda bisa menggunakan fitur "Tests" pada endpoint login untuk menyimpan token secara otomatis ke variabel environment Postman).*

---

## 3. Detail Endpoint API

Semua endpoint kecuali `/api/login` wajib menyertakan **Bearer Token** pada *Header Authorization*:
`Authorization: Bearer <token_anda>`
Serta sangat disarankan untuk mengatur header `Accept: application/json`.

### 3.1. Autentikasi (`Auth`)

#### 1. Login
- **Endpoint:** `POST /api/login`
- **Akses:** Publik
- **Deskripsi:** Mendapatkan bearer token untuk mengakses endpoint lainnya.
- **Request Body (JSON):**
  ```json
  {
      "username": "admin",
      "password": "password"
  }
  ```
- **Response Success (200):**
  ```json
  {
      "access_token": "1|abcde...",
      "token_type": "Bearer"
  }
  ```

#### 2. Logout
- **Endpoint:** `POST /api/logout`
- **Akses:** Semua Role (Membutuhkan Token)
- **Deskripsi:** Menghapus token yang sedang digunakan.
- **Response Success (200):**
  ```json
  {
      "message": "Logged out"
  }
  ```

#### 3. Profil Pengguna
- **Endpoint:** `GET /api/profile`
- **Akses:** Semua Role (Membutuhkan Token)
- **Deskripsi:** Menampilkan data pengguna yang sedang login.
- **Response Success (200):**
  ```json
  {
      "id_pengguna": 1,
      "nama_pengguna": "Admin",
      "Username": "admin",
      "role": "admin",
      "Unit_Kerja": "IT",
      "Status_Akun": "aktif"
  }
  ```

### 3.2. Manajemen Pengguna (`Users`) - Khusus Admin

#### 1. Lihat Semua Pengguna
- **Endpoint:** `GET /api/users`
- **Response Success (200):** Menampilkan array JSON berisi list semua akun pengguna.

#### 2. Buat Pengguna Baru
- **Endpoint:** `POST /api/users`
- **Request Body (JSON):**
  ```json
  {
      "nama_pengguna": "Budi",
      "Username": "budi",
      "password": "123",
      "email": "budi@mail.com",
      "role": "user",
      "Unit_Kerja": "IT",
      "Status_Akun": "aktif"
  }
  ```

#### 3. Detail, Update, dan Hapus Pengguna
- **GET** `/api/users/{id}` : Melihat profil pengguna berdasarkan ID.
- **PUT** `/api/users/{id}` : Memperbarui data pengguna. Parameter body sama persis seperti saat Create (password boleh tidak dikirim jika tidak ingin diubah).
- **DELETE** `/api/users/{id}` : Menghapus data pengguna tersebut dari sistem.

### 3.3. Manajemen Aset (`Assets`)

#### 1. Lihat Semua Aset
- **Endpoint:** `GET /api/assets`
- **Akses:** Semua Role
- **Deskripsi:** Mengembalikan daftar lengkap semua aset, termasuk status ketersediaan dan foto.

#### 2. Lihat Aset Tersedia
- **Endpoint:** `GET /api/assets/available`
- **Akses:** Semua Role
- **Deskripsi:** Mengembalikan daftar aset yang hanya dalam status `tersedia`.

#### 3. Detail Aset
- **Endpoint:** `GET /api/assets/{id}`
- **Akses:** Semua Role
- **Deskripsi:** Mengembalikan info satu aset spesifik beserta data relasi QR code nya.

#### 4. Tambah Aset Baru (Admin)
- **Endpoint:** `POST /api/assets`
- **Deskripsi:** Memasukkan aset ke dalam sistem. QR Code unik akan di-_generate_ secara otomatis.
- **Request Body (FormData / `multipart/form-data`):**
  - `nama_Aset`: (String) Wajib, contoh: Proyektor
  - `status_aset`: (String) Wajib, contoh: tersedia
  - `Row`: (String) Wajib, contoh: A1
  - `foto_aset`: (File Gambar, Opsional) Format jpeg/png/jpg/webp, max 2MB.

#### 5. Update & Hapus Aset (Admin)
- **PUT** `/api/assets/{id}` : Mengubah informasi aset yang ada. <br>*Tips Postman:* Jika ingin mengubah/mengirim `foto_aset` ke endpoint ini, harap gunakan metode HTTP **POST** dan selipkan key `_method` dengan value `PUT` di tab form-data.
- **DELETE** `/api/assets/{id}` : Menghapus keseluruhan data aset secara permanen.

#### 6. Monitoring & QR Code (Admin)
- **GET** `/api/assets/status` : Data statistik (rekap) jumlah aset tersedia versus dipinjam.
- **GET** `/api/assets/borrowed` : List aset spesifik yang saat ini sedang dipinjam oleh user lain.
- **POST** `/api/assets/{id}/generate-qr` : Mengubah (reset) ulang QR Code untuk suatu aset (secara default tidak perlu karena QR code sudah terbuat otomatis di awal penambahan aset).
- **POST** `/api/scan-qr` : Mengecek keaslian QR Code aset.
  - **Request Body (JSON):** `{ "qr_code": "ASET-QR-AST-001-xxx" }`

### 3.4. Peminjaman & Pengembalian (`Loans` & `Returns`)

#### 1. Pengajuan Peminjaman
- **Endpoint:** `POST /api/loans`
- **Akses:** Semua Role
- **Deskripsi:** Mencatat transaksi peminjaman. Sistem otomatis mengaitkannya dengan *user* yang sedang login (lewat token). Aset yang berhasil dipinjam akan dikunci statusnya menjadi `dipinjam`.
- **Request Body (JSON):**
  ```json
  {
      "id_Aset": 1,
      "Tanggal_kembali": "2026-06-20 17:00:00"
  }
  ```

#### 2. Riwayat Peminjaman Sendiri
- **Endpoint:** `GET /api/loans/my-history`
- **Akses:** Semua Role
- **Deskripsi:** Menampilkan catatan semua transaksi peminjaman yang pernah dilakukan oleh akun pengguna Anda (yg sedang login).

#### 3. Pengembalian Aset
- **Endpoint:** `POST /api/returns`
- **Akses:** Semua Role
- **Deskripsi:** Menyelesaikan transaksi peminjaman (pengembalian). Status `peminjaman` diubah jadi `dikembalikan` dan aset bisa diakses (menjadi `tersedia`) oleh orang lain lagi.
- **Request Body (JSON):**
  ```json
  {
      "id_peminjaman": 1,
      "kondisi_Aset": "baik"
  }
  ```

#### 4. Khusus Admin (Monitoring Peminjaman & Pengembalian)
- **GET** `/api/loans` : Menarik rekap semua transaksi peminjaman dari semua user di sistem.
- **GET** `/api/loans/{id}` : Detail suatu spesifik peminjaman.
- **GET** `/api/returns` : Menarik rekap riwayat barang apa saja yang sudah dikembalikan oleh user mana saja.

### 3.5. Log & Laporan (Khusus Admin)

- **GET** `/api/logs` : Memantau jejak log aktivitas (action log user seperti meminjam barang dan mengembalikan barang, lengkap beserta tanggal dan waktu).
- **GET** `/api/reports/inventory` : Endpoint laporan khusus status akhir dan posisi seluruh aset (inventaris).
- **GET** `/api/reports/loans` : Endpoint laporan khusus untuk semua pencatatan peminjaman.
- **GET** `/api/reports/returns` : Endpoint laporan khusus rekap data pengembalian.

---

## 4. Simulasi Alur Kerja (Skenario Penggunaan)

Untuk memahami bagaimana aplikasi bekerja dari ujung ke ujung, Anda bisa menguji alur (flow) ini di Postman secara berurutan:

1. **Login User**: Panggil `POST /api/login` menggunakan kredensial yang ada (contoh: user dengan role "user"). Pasang token di Header `Authorization` untuk request selanjutnya.
2. **Cek Aset Tersedia**: Panggil `GET /api/assets/available`. Pilih salah satu ID aset (misal: `Id_Aset: 1`).
3. **Pinjam Aset**: Panggil `POST /api/loans` dan masukkan `id_Aset: 1` di dalam body. Status aset sekarang otomatis menjadi *dipinjam*.
4. **Cek Riwayat Sendiri**: Panggil `GET /api/loans/my-history` untuk memastikan transaksi peminjaman tercatat untuk Anda. Catat ID peminjaman (misal: `id_peminjaman: 1`).
5. **Kembalikan Aset**: Panggil `POST /api/returns` dan masukkan `id_peminjaman: 1` ke dalam body beserta kondisi saat dikembalikan.
6. **Cek Aktivitas (Hanya Admin)**: Panggil `GET /api/logs` menggunakan akun **Admin** untuk memverifikasi bahwa sistem merekam waktu saat user meminjam dan mengembalikan aset.