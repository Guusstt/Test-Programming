# Sistem Klinik CI4
Sistem Klinik ini dibuat menggunakan CodeIgniter 4 (CI4) dan MySQL, dengan fitur login multi-level dan manajemen data pasien, kunjungan, asesmen, dan diagnosis. Sistem ini merupakan implementasi dari SPA (Single Page Application) menggunakan jQuery, Bootstrap, dan AJAX.
## Fitur Utama

| Role           | Hak Akses                                                 |
| -------------- | --------------------------------------------------------- |
|   Superadmin   | Akses penuh ke semua fitur.                               |
|     Admisi     | CRUD Pendaftaran dan Kunjungan, tidak bisa lihat Asesmen. |
|    Perawat     | View Pendaftaran & Kunjungan, CRUD Asesmen & Diagnosis.   |


## Fungsionalitas 
1. SPA dengan jQuery: navigasi menu tanpa reload halaman.
2. CRUD data: menggunakan modal Bootstrap + AJAX.
3. Validasi form: semua field wajib diisi, menggunakan SweetAlert.
4. Feedback aksi: setiap aksi (sukses/gagal) muncul notifikasi SweetAlert.
5. Datatables: menampilkan tabel interaktif untuk semua data.
6. Cetak detail data: setiap baris bisa dicetak menggunakan window.print().
7. Import dummy data pasien: dari JSONPlaceholder.

## Struktur Database
Tabel user
| Kolom    | Tipe                                  | Keterangan      |
| -------- | ------------------------------------- | --------------- |
| id       | INT PK AI                             | ID user         |
| name     | VARCHAR                               | Nama lengkap    |
| email    | VARCHAR                               | Email login     |
| password | VARCHAR                               | Password (hash) |
| role     | ENUM('superadmin','admisi','perawat') | Hak akses       |

Tabel pasien
| Kolom   | Tipe      | Keterangan    |
| ------- | --------- | ------------- |
| id      | INT PK AI | ID pasien     |
| nama    | VARCHAR   | Nama pasien   |
| alamat  | VARCHAR   | Alamat pasien |
| telepon | VARCHAR   | No. telepon   |
| email   | VARCHAR   | Email pasien  |

Tabel kunjungan
| Kolom      | Tipe      | Keterangan        |
| ---------- | --------- | ----------------- |
| id         | INT PK AI | ID kunjungan      |
| pasien_id  | INT FK    | ID pasien         |
| tanggal    | DATETIME  | Tanggal kunjungan |
| keterangan | TEXT      | Catatan kunjungan |

Tabel asesmen
| Kolom        | Tipe      | Keterangan              |
| ------------ | --------- | ----------------------- |
| id           | INT PK AI | ID asesmen              |
| kunjungan_id | INT FK    | ID kunjungan            |
| perawat_id   | INT FK    | ID perawat              |
| diagnosis    | TEXT      | Hasil diagnosa          |
| tindakan     | TEXT      | Tindakan yang diberikan |


## Instalasi
1. Clone repository:
   git clone https://github.com/username/Test-Programming.git
   cd Test-Programming
   
2. Copy .env.example menjadi .env dan sesuaikan konfigurasi database:
   database.default.hostname = localhost
   database.default.database = nama_database
   database.default.username = root
   database.default.password =
   
3. Jalankan migrasi (jika menggunakan migrasi CI4):
   php spark migrate
   
4. Jalankan server CI4:
   php spark serve

5. Buka browser:
   http://localhost:8080

## Teknologi
1. Backend: CodeIgniter 4 (PHP)
2. Database: MySQL
3. Frontend: Bootstrap 5, jQuery, AJAX, SweetAlert, DataTables
