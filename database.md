# Dokumentasi Struktur Database

Berikut adalah struktur database dari aplikasi peminjaman aset berdasarkan file migration Laravel yang ada.

## Tabel Utama

### 1. `users`
Menyimpan data pengguna (baik admin maupun user biasa).
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_pengguna` | BigInt | Primary Key, Auto Increment |
| `nama_pengguna` | String (100) | Nama lengkap pengguna |
| `Username` | String (50) | Username untuk login, **Unique** |
| `password` | String (255) | Password terenkripsi |
| `email` | String (100) | Alamat email, **Unique** |
| `role` | Enum | `'admin'`, `'user'`. Default: `'user'` |
| `Unit_Kerja` | String (100) | Unit kerja pengguna, *Nullable* |
| `Status_Akun` | String (50) | Status akun, Default: `'aktif'` |
| `created_at` | Timestamp | Waktu pembuatan data |
| `updated_at` | Timestamp | Waktu perubahan data |

### 2. `aset`
Menyimpan data aset/barang yang bisa dipinjam.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `Id_Aset` | BigInt | Primary Key, Auto Increment |
| `nama_Aset` | String (100) | Nama aset/barang |
| `status_aset` | Enum | `'tersedia'`, `'dipinjam'` |
| `Row` | String (50) | Lokasi/rak aset, *Nullable* |
| `created_at` | Timestamp | Waktu pembuatan data |
| `updated_at` | Timestamp | Waktu perubahan data |

### 3. `peminjaman`
Menyimpan riwayat transaksi peminjaman aset oleh pengguna.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `Id_peminjaman` | BigInt | Primary Key, Auto Increment |
| `id_pengguna` | BigInt | Foreign Key ke `users.id_pengguna`, *Cascade Delete* |
| `id_Aset` | BigInt | Foreign Key ke `aset.Id_Aset`, *Cascade Delete* |
| `Tanggal_pinjam` | DateTime | Waktu saat peminjaman dilakukan |
| `Tanggal_kembali` | DateTime | Waktu tenggat pengembalian peminjaman |
| `created_at` | Timestamp | Waktu pembuatan data |
| `updated_at` | Timestamp | Waktu perubahan data |

### 4. `pengembalian`
Menyimpan data pengembalian dari aset yang telah dipinjam.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_pengembalian` | BigInt | Primary Key, Auto Increment |
| `id_peminjaman` | BigInt | Foreign Key ke `peminjaman.Id_peminjaman`, *Cascade Delete* |
| `tanggal_kembali` | DateTime | Waktu aset dikembalikan secara aktual |
| `kondisi_Aset` | Enum | `'baik'`, `'rusak ringan'`, `'rusak berat'` |
| `created_at` | Timestamp | Waktu pembuatan data |
| `updated_at` | Timestamp | Waktu perubahan data |

### 5. `table_qr_code`
Menyimpan data QR code unik yang digenerate untuk masing-masing aset guna keperluan tracking.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_qr` | BigInt | Primary Key, Auto Increment |
| `id_Aset` | BigInt | Foreign Key ke `aset.Id_Aset`, *Cascade Delete* |
| `tanggal_generate` | DateTime | Waktu QR Code dibuat |
| `kode_unik` | String (100) | Kode QR unik, **Unique** |
| `created_at` | Timestamp | Waktu pembuatan data |
| `updated_at` | Timestamp | Waktu perubahan data |

### 6. `log_aktivitas`
Menyimpan log aktivitas pengguna di dalam sistem untuk keperluan audit dan monitoring.
| Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_log` | BigInt | Primary Key, Auto Increment |
| `id_pengguna` | BigInt | Foreign Key ke `users.id_pengguna`, *Cascade Delete* |
| `Aktivitas` | String (255) | Nama/Jenis aktivitas yang dilakukan |
| `waktu` | DateTime | Waktu aktivitas dilakukan |
| `deskripsi` | String (255) | Detail dari aktivitas, *Nullable* |
| `created_at` | Timestamp | Waktu pembuatan data |
| `updated_at` | Timestamp | Waktu perubahan data |

## Relasi Tabel (Entity Relationship)

1. **`users` (1) --- (N) `peminjaman`**
   Seorang user dapat melakukan banyak peminjaman.
2. **`aset` (1) --- (N) `peminjaman`**
   Sebuah aset bisa memiliki banyak riwayat peminjaman.
3. **`peminjaman` (1) --- (1/N) `pengembalian`**
   Sebuah transaksi peminjaman memiliki proses pengembalian terkait (normalnya 1 to 1 per transaksi).
4. **`aset` (1) --- (N) `table_qr_code`**
   Sebuah aset bisa memiliki QR Code (biasanya 1 aktif).
5. **`users` (1) --- (N) `log_aktivitas`**
   Seorang pengguna dapat memiliki banyak log aktivitas.

> **Catatan:**
> Terdapat juga tabel default bawaan Laravel seperti `sessions`, `password_reset_tokens`, `jobs`, dan `cache` yang tidak didokumentasikan di sini karena merupakan fungsionalitas core framework.
