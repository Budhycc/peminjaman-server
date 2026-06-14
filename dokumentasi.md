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

### 3.1. Autentikasi (`Auth`)

| Method | Endpoint | Akses | Keterangan |
| :--- | :--- | :--- | :--- |
| `POST` | `/api/login` | Publik | Login user untuk mendapatkan token. Body: `{ "username": "admin", "password": "password" }`. |
| `POST` | `/api/logout`| Semua Role | Menghapus token (Logout). Membutuhkan *Bearer Token*. |
| `GET`  | `/api/profile` | Semua Role | Menampilkan data user yang sedang login saat ini. |

---

### 3.2. Manajemen Pengguna (`Users`)

*Membutuhkan Bearer Token. Khusus **Admin**.*

| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `GET` | `/api/users` | Menampilkan semua pengguna. |
| `POST` | `/api/users` | Membuat pengguna baru. <br>**Body:** `{ "nama": "Budi", "username": "budi", "password": "123", "email": "budi@mail.com", "role": "user" }` |
| `GET` | `/api/users/{id}` | Menampilkan detail pengguna berdasarkan ID. |
| `PUT` | `/api/users/{id}` | Memperbarui data pengguna. |
| `DELETE` | `/api/users/{id}` | Menghapus pengguna. |

---

### 3.3. Manajemen Aset (`Assets`)

*Membutuhkan Bearer Token.*

| Method | Endpoint | Akses | Keterangan |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/assets` | Semua Role | Menampilkan seluruh aset. |
| `GET` | `/api/assets/available` | Semua Role | Menampilkan list spesifik aset yang statusnya sedang `tersedia`. |
| `GET` | `/api/assets/{id}`| Semua Role | Menampilkan detail sebuah aset. |
| `POST` | `/api/assets` | Admin | Menambahkan aset baru. <br>**Body JSON:** `{ "kode_aset": "AST-001", "nama_aset": "Proyektor", "kategori": "Elektronik", "merk": "Epson", "lokasi": "Ruang A", "kondisi": "baik", "status": "tersedia" }` |
| `PUT` | `/api/assets/{id}`| Admin | Mengubah data aset. |
| `DELETE` | `/api/assets/{id}`| Admin | Menghapus data aset dari sistem. |

**Monitoring Status & QR Code Aset:**

| Method | Endpoint | Akses | Keterangan |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/assets/status` | Admin | Menampilkan total rekap jumlah aset yang *tersedia* dan *dipinjam*. |
| `GET` | `/api/assets/borrowed`| Admin | Menampilkan list spesifik aset yang statusnya sedang `dipinjam`. |
| `POST` | `/api/assets/{id}/generate-qr` | Admin | Membangkitkan QR Code acak untuk suatu aset. |
| `POST` | `/api/scan-qr` | Admin | Validasi QR Code aset. <br>**Body:** `{ "qr_code": "ASET-QR-AST-001-xxx" }` |

---

### 3.4. Peminjaman & Pengembalian (`Loans` & `Returns`)

*Membutuhkan Bearer Token.*

#### A. Peminjaman Aset (Loans)
Proses pencatatan ketika user meminjam barang. Status aset akan otomatis berubah menjadi `dipinjam`.

- **`GET /api/loans/my-history`** (Semua Role): Lihat transaksi peminjaman milik Anda sendiri berdasarkan akun yang login.
- **`POST /api/loans`** (Semua Role): Buat pengajuan peminjaman.
  **Body (JSON):**
  ```json
  {
      "id_aset": 1,
      "rencana_kembali": "2026-06-20 17:00:00",
      "catatan": "Pinjam untuk keperluan presentasi"
  }
  ```
  *(Catatan: `id_user` otomatis diambil dari token login. Aset yang dipinjam harus berstatus "tersedia").*
- **`GET /api/loans`** (Admin): Lihat seluruh transaksi peminjaman (untuk Admin).
- **`GET /api/loans/{id}`** (Admin): Lihat detail transaksi peminjaman spesifik.

#### B. Pengembalian Aset (Returns)
Proses ketika user mengembalikan barang. Status peminjaman menjadi `dikembalikan` dan status aset kembali `tersedia`.

- **`POST /api/returns`** (Semua Role): Mencatat pengembalian aset.
  **Body (JSON):**
  ```json
  {
      "id_peminjaman": 1,
      "kondisi_kembali": "baik", // pilihan: baik, rusak ringan, rusak berat
      "catatan": "Aset dikembalikan dengan aman"
  }
  ```
- **`GET /api/returns`** (Admin): Lihat riwayat semua pengembalian.

---

### 3.5. Log & Laporan (Admin Only)

*Membutuhkan Bearer Token. Khusus **Admin**.*

| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `GET` | `/api/logs` | Menampilkan log aktivitas seluruh user yang terekam (seperti aktivitas peminjaman/pengembalian). |
| `GET` | `/api/reports/inventory` | Menampilkan laporan seluruh inventaris (semua aset). |
| `GET` | `/api/reports/loans` | Menampilkan laporan khusus data peminjaman. |
| `GET` | `/api/reports/returns`| Menampilkan laporan khusus data pengembalian. |

---

## 4. Simulasi Alur Kerja (Skenario Penggunaan)

Untuk memahami bagaimana aplikasi bekerja dari ujung ke ujung, Anda bisa menguji alur (flow) ini di Postman secara berurutan:

1. **Login User**: Panggil `POST /api/login` menggunakan kredensial yang ada (contoh: user dengan role "user"). Pasang token di Header `Authorization` untuk request selanjutnya.
2. **Cek Aset Tersedia**: Panggil `GET /api/assets/available`. Pilih salah satu ID aset (misal: `id_aset: 1`).
3. **Pinjam Aset**: Panggil `POST /api/loans` dan masukkan `id_aset: 1` di dalam body. Status aset sekarang otomatis menjadi *dipinjam*.
4. **Cek Riwayat Sendiri**: Panggil `GET /api/loans/my-history` untuk memastikan transaksi peminjaman tercatat untuk Anda. Catat ID peminjaman (misal: `id_peminjaman: 1`).
5. **Kembalikan Aset**: Panggil `POST /api/returns` dan masukkan `id_peminjaman: 1` ke dalam body beserta kondisi saat dikembalikan.
6. **Cek Aktivitas (Hanya Admin)**: Panggil `GET /api/logs` menggunakan akun **Admin** untuk memverifikasi bahwa sistem merekam waktu saat user meminjam dan mengembalikan aset.