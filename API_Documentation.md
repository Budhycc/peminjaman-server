# Dokumentasi API Peminjaman Server

**Base URL**: `/api`

Semua permintaan API (kecuali Login) membutuhkan Autentikasi menggunakan **Bearer Token** yang didapatkan dari respons login.
Harap selalu menyertakan header berikut pada setiap request:
```http
Accept: application/json
Authorization: Bearer <token_anda>
```

---

## 1. Autentikasi (Authentication)

### 1.1. Login User
Digunakan untuk mendapatkan access token.

- **URL:** `/api/login`
- **Method:** `POST`
- **Headers:**
  - `Accept: application/json`
  - `Content-Type: application/json`
- **Body Request:**
  ```json
  {
      "Username": "johndoe",
      "password": "password123"
  }
  ```
- **Response Sukses (200 OK):**
  ```json
  {
      "access_token": "1|abcdef123456...",
      "message": "Login successful",
      "type": "Bearer",
      "user": {
          "id_pengguna": 1,
          "nama_pengguna": "John Doe",
          "Username": "johndoe",
          "email": "johndoe@example.com",
          "role": "admin",
          "Unit_Kerja": "IT",
          "Status_Akun": "aktif"
      }
  }
  ```
- **Response Gagal (401 Unauthorized - Kredensial Salah):**
  ```json
  {
      "message": "Invalid credentials"
  }
  ```
- **Response Gagal (422 Unprocessable Entity - Validasi Gagal):**
  ```json
  {
      "message": "The given data was invalid.",
      "errors": {
          "Username": ["The Username field is required."]
      }
  }
  ```

### 1.2. Logout User
Menghapus token yang sedang digunakan (Revoke Token).

- **URL:** `/api/logout`
- **Method:** `POST`
- **Headers:**
  - `Accept: application/json`
  - `Authorization: Bearer <token>`
- **Response Sukses (200 OK):**
  ```json
  {
      "message": "Logout successful"
  }
  ```

### 1.3. Profil User Login
Mendapatkan data dari user yang sedang terautentikasi (berdasarkan token).

- **URL:** `/api/profile`
- **Method:** `GET`
- **Headers:**
  - `Accept: application/json`
  - `Authorization: Bearer <token>`
- **Response Sukses (200 OK):**
  ```json
  {
      "id_pengguna": 1,
      "nama_pengguna": "John Doe",
      "Username": "johndoe",
      "email": "johndoe@example.com",
      "role": "admin",
      "Unit_Kerja": "IT",
      "Status_Akun": "aktif"
  }
  ```

---

## 2. Manajemen User (Khusus Admin)

Endpoint ini membutuhkan user yang login dengan `role` sebagai `admin`.

### 2.1. Ambil Semua User
Mendapatkan daftar seluruh pengguna.

- **URL:** `/api/users`
- **Method:** `GET`
- **Headers:** `Accept: application/json`, `Authorization: Bearer <token>`
- **Response Sukses (200 OK):**
  ```json
  [
      {
          "id_pengguna": 1,
          "nama_pengguna": "John Doe",
          "Username": "johndoe",
          "email": "johndoe@example.com",
          "role": "admin",
          "Unit_Kerja": "IT"
      }
  ]
  ```

### 2.2. Tambah User Baru
- **URL:** `/api/users`
- **Method:** `POST`
- **Headers:** `Accept: application/json`, `Content-Type: application/json`, `Authorization: Bearer <token>`
- **Body Request:**
  ```json
  {
      "nama_pengguna": "Jane Doe",         // String, Required, Max: 100
      "Username": "janedoe",             // String, Required, Max: 50, Unique
      "password": "password123",         // String, Required, Min: 6
      "email": "jane@example.com",       // String (Email), Required, Unique
      "role": "user",                    // "admin" atau "user", Required
      "Unit_Kerja": "Finance"            // String, Optional
  }
  ```
- **Response Sukses (201 Created):**
  ```json
  {
      "id_pengguna": 2,
      "nama_pengguna": "Jane Doe",
      "Username": "janedoe",
      "email": "jane@example.com",
      "role": "user",
      "Unit_Kerja": "Finance"
  }
  ```
- **Response Gagal (422 Unprocessable Entity - Contoh: Email sudah dipakai):**
  ```json
  {
      "message": "The given data was invalid.",
      "errors": {
          "email": ["The email has already been taken."]
      }
  }
  ```

### 2.3. Ambil Detail User
- **URL:** `/api/users/{id}`
- **Method:** `GET`
- **Path Parameters:** `id` (ID Pengguna)
- **Response Sukses (200 OK):** Mengembalikan object User.
- **Response Gagal (404 Not Found):** Jika user tidak ditemukan.

### 2.4. Update User
- **URL:** `/api/users/{id}`
- **Method:** `PUT`
- **Body Request (Opsional, hanya data yang berubah):**
  ```json
  {
      "nama_pengguna": "Jane Doe Updated",
      "password": "newpassword123",
      "Unit_Kerja": "HR"
  }
  ```
- **Response Sukses (200 OK):** Mengembalikan object User yang sudah diupdate.

### 2.5. Hapus User
- **URL:** `/api/users/{id}`
- **Method:** `DELETE`
- **Response Sukses (200 OK):**
  ```json
  {
      "message": "User deleted successfully"
  }
  ```

---

## 3. Manajemen Aset

### 3.1. Ambil Semua Aset
- **URL:** `/api/assets`
- **Method:** `GET`
- **Role:** User, Admin
- **Response Sukses (200 OK):**
  ```json
  [
      {
          "Id_Aset": 1,
          "nama_Aset": "Proyektor Epson",
          "status_aset": "tersedia",
          "jumlah": 10,
          "jenis_barang": "Elektronik",
          "tempat_barang": "Ruang Server",
          "foto_aset": "fotos/abc.jpg"
      }
  ]
  ```

### 3.2. Ambil Aset Tersedia (Bisa Dipinjam)
- **URL:** `/api/assets/available`
- **Method:** `GET`
- **Role:** User, Admin
- **Keterangan:** Hanya memunculkan aset yang `status_aset = tersedia`.

### 3.3. Ambil Aset yang Sedang Dipinjam
- **URL:** `/api/assets/borrowed`
- **Method:** `GET`
- **Role:** Admin

### 3.4. Ambil Status Rekap Aset
- **URL:** `/api/assets/status`
- **Method:** `GET`
- **Role:** Admin
- **Response Sukses (200 OK):**
  ```json
  {
      "tersedia": 15,
      "dipinjam": 3,
      "total": 18
  }
  ```

### 3.5. Detail Aset
- **URL:** `/api/assets/{id}`
- **Method:** `GET`
- **Role:** User, Admin

### 3.6. Tambah Aset Baru (Upload Gambar)
- **URL:** `/api/assets`
- **Method:** `POST`
- **Role:** Admin
- **Headers:** `Content-Type: multipart/form-data`
- **Body Request (Form-Data):**
  - `nama_Aset`: Text (Required) - Contoh: "Laptop ASUS"
  - `status_aset`: Text (Required) - Pilihan: `tersedia` atau `dipinjam`
  - `jumlah`: Number (Required) - Minimal 1, total stok aset awal.
  - `jenis_barang`: Text (Required) - Contoh: "Elektronik"
  - `tempat_barang`: Text (Optional) - Contoh: "Lemari 2"
  - `foto_aset`: File (Optional) - Hanya: jpeg, png, jpg, gif, webp. Maksimal 2MB.
- **Response Sukses (201 Created):**
  ```json
  {
      "Id_Aset": 2,
      "nama_Aset": "Laptop ASUS",
      "status_aset": "tersedia",
      "jumlah": 5,
      "jenis_barang": "Elektronik",
      "tempat_barang": "Lemari 2",
      "foto_aset": "fotos/xyz.jpg",
      "qr_code": {
          "kode_unik": "AST-2-A1B2C3"
      }
  }
  ```

### 3.7. Update Aset
- **URL:** `/api/assets/{id}`
- **Method:** `POST` *(Menggunakan method spoofing `_method=PUT` di form-data)*
- **Role:** Admin
- **Body Request (Form-Data, Opsional):**
  - `_method`: "PUT"
  - `nama_Aset`: Text
  - `status_aset`: Text
  - `jumlah`: Number (Optional)
  - `jenis_barang`: Text
  - `tempat_barang`: Text
  - `foto_aset`: File
- **Keterangan:** Jika mengirim file (gambar) pada update, PHP membutuhkan request tipe Multipart form-data yang tidak mendukung method PUT secara native. Oleh karena itu gunakan method HTTP POST dan sertakan key `_method` dengan value `PUT`.

### 3.8. Hapus Aset
- **URL:** `/api/assets/{id}`
- **Method:** `DELETE`
- **Role:** Admin

### 3.9. Generate Ulang QR Code Aset
- **URL:** `/api/assets/{id}/generate-qr`
- **Method:** `POST`
- **Role:** Admin
- **Response Sukses (200 OK):**
  ```json
  {
      "qr_code": "AST-2-XYZ987",
      "message": "QR Code generated successfully"
  }
  ```

### 3.10. Scan QR Code
Mendapatkan data aset dari kode unik QR.
- **URL:** `/api/scan-qr`
- **Method:** `POST`
- **Role:** User, Admin
- **Headers:** `Content-Type: application/json`
- **Body Request:**
  ```json
  {
      "qr_code": "AST-1-A1B2C3"
  }
  ```
- **Response Sukses (200 OK):** Mengembalikan object Aset.
- **Response Gagal (404 Not Found):**
  ```json
  {
      "message": "Aset not found"
  }
  ```

---

## 4. Peminjaman & Pengembalian

### 4.1. Lihat Riwayat Peminjaman Saya
- **URL:** `/api/loans/my-history`
- **Method:** `GET`
- **Role:** User
- **Response Sukses (200 OK):** Menampilkan daftar peminjaman aset beserta relasinya dengan tabel Aset dan Pengembalian.

### 4.2. Ajukan Peminjaman Aset
- **URL:** `/api/loans`
- **Method:** `POST`
- **Role:** User
- **Body Request:**
  ```json
  {
      "Id_Aset": 2,
      "jumlah": 2
  }
  ```
- **Parameter Body:**
  - `Id_Aset`: Number (Required, exists in table aset)
  - `jumlah`: Number (Required, minimal 1)
  - `catatan`: Text (Optional)
- **Response Sukses (201 Created):**
  ```json
  {
      "Id_peminjaman": 1,
      "id_pengguna": 1,
      "Id_Aset": 1,
      "jumlah": 2,
      "Tanggal_pinjam": "2026-08-21 10:00:00"
  }
  ```
- **Response Gagal (400 Bad Request - Aset tidak tersedia):**
  ```json
  {
      "message": "Aset is not available for borrowing"
  }
  ```

### 4.3. Ajukan Pengembalian Aset
- **URL:** `/api/returns`
- **Method:** `POST`
- **Role:** User
- **Body Request:**
  ```json
  {
      "Id_peminjaman": 1,                  // ID Peminjaman dari tabel peminjaman
      "jumlah": 2,                         // Jumlah yang dikembalikan, Required, tidak boleh melebihi sisa pinjaman
      "kondisi_Aset": "baik",              // Pilihan: "baik", "rusak"
      "catatan": "Kondisi baik"            // String, Optional
  }
  ```
- **Response Sukses (201 Created):**
  ```json
  {
      "Id_pengembalian": 1,
      "id_peminjaman": 1,
      "jumlah": 2,
      "tanggal_kembali": "2026-08-25 15:30:00",
      "kondisi_Aset": "baik"
  }
  ```
- **Response Gagal (400 Bad Request - Sudah dikembalikan):**
  ```json
  {
      "message": "Aset has already been returned"
  }
  ```

### 4.4. Lihat Semua Peminjaman (Log/History)
- **URL:** `/api/loans`
- **Method:** `GET`
- **Role:** Admin
- **Response:** Daftar semua peminjaman beserta user dan aset terkait.

### 4.5. Lihat Semua Pengembalian
- **URL:** `/api/returns`
- **Method:** `GET`
- **Role:** Admin

---

## 5. Log & Laporan (Khusus Admin)

*(Endpoint berikut hanya bisa diakses oleh role `admin`)*

### 5.1. Log Aktivitas User
- **URL:** `/api/logs`
- **Method:** `GET`
- **Response:** Mengembalikan semua log aktivitas yang dilakukan oleh user (misalnya peminjaman dan pengembalian).

### 5.2. Laporan Inventory
- **URL:** `/api/reports/inventory`
- **Method:** `GET`

### 5.3. Laporan Peminjaman
- **URL:** `/api/reports/loans`
- **Method:** `GET`

### 5.4. Laporan Pengembalian
- **URL:** `/api/reports/returns`
- **Method:** `GET`
