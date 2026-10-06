# Tugas Mandiri Modul 6
## Perancangan ERD E-Library Kampus

### Identitas
- Nama: Andi Rosiqah Salsabila
- NIM: D121241030
- Mata Kuliah: Pemrograman Web B
- Tugas Modul 6: Perancangan ERD E-Library Kampus

---

## 1. Deskripsi Sistem

E-Library Kampus merupakan sistem basis data relasional yang digunakan untuk mengelola proses peminjaman buku pada perpustakaan kampus. Sistem menyimpan data mahasiswa, buku, penerbit, serta transaksi peminjaman dan pengembalian buku.

Setiap mahasiswa dapat melakukan beberapa transaksi peminjaman. Setiap buku dapat muncul dalam beberapa transaksi peminjaman pada waktu yang berbeda.
Setiap buku diterbitkan oleh satu penerbit, sedangkan satu penerbit dapat menerbitkan banyak buku.

## 2. Desain ERD Logis

Sistem E-Library Kampus memiliki empat entitas utama, yaitu `mahasiswa`, `buku`, `penerbit`, dan `transaksi_peminjaman`.

Relasi antarentitas adalah sebagai berikut:

1. Satu mahasiswa dapat memiliki banyak transaksi peminjaman.
2. Satu buku dapat tercatat dalam banyak transaksi peminjaman pada waktu yang berbeda.
3. Satu penerbit dapat menerbitkan banyak buku.
4. Setiap transaksi peminjaman berhubungan dengan satu mahasiswa dan satu buku.

Cardinality:

- `mahasiswa` 1 : N `transaksi_peminjaman`
- `buku` 1 : N `transaksi_peminjaman`
- `penerbit` 1 : N `buku`

## 3. Identifikasi Entitas dan Atribut

### 3.1 Entitas Mahasiswa

| Atribut | Keterangan |
|---|---|
| `nim` | Primary Key, identitas unik mahasiswa |
| `nama_mahasiswa` | Nama lengkap mahasiswa |
| `email` | Email mahasiswa |
| `program_studi` | Program studi mahasiswa |

### 3.2 Entitas Penerbit

| Atribut | Keterangan |
|---|---|
| `penerbit_id` | Primary Key, identitas unik penerbit |
| `nama_penerbit` | Nama penerbit |
| `alamat_penerbit` | Alamat penerbit |

### 3.3 Entitas Buku

| Atribut | Keterangan |
|---|---|
| `buku_id` | Primary Key, identitas unik buku |
| `isbn` | Nomor ISBN buku |
| `judul` | Judul buku |
| `tahun_terbit` | Tahun penerbitan buku |
| `stok` | Jumlah buku yang tersedia |
| `penerbit_id` | Foreign Key yang merujuk ke `penerbit.penerbit_id` |

### 3.4 Entitas Transaksi Peminjaman

| Atribut | Keterangan |
|---|---|
| `peminjaman_id` | Primary Key, identitas unik transaksi |
| `nim` | Foreign Key yang merujuk ke `mahasiswa.nim` |
| `buku_id` | Foreign Key yang merujuk ke `buku.buku_id` |
| `tanggal_pinjam` | Tanggal buku dipinjam |
| `tanggal_jatuh_tempo` | Batas waktu pengembalian buku |
| `tanggal_kembali` | Tanggal buku dikembalikan |
| `status` | Status transaksi peminjaman |

## 4. Simulasi Normalisasi

### 4.1 Bentuk Tidak Normal (UNF)

Pada awalnya seluruh data mahasiswa dan buku yang dipinjam dapat disimpan dalam satu tabel besar seperti berikut:

| NIM | Nama Mahasiswa | Email | Program Studi | Buku Dipinjam |
|---|---|---|---|---|
| D121241001 | Andi | andi@student.unhas.ac.id | Teknik Informatika | {B001, Basis Data, Informatika Press, 2026-10-01, 2026-10-08}, {B002, Pemrograman Web, Media Teknologi, 2026-10-01, 2026-10-08} |
| D121241002 | Rina | rina@student.unhas.ac.id | Teknik Informatika | {B001, Basis Data, Informatika Press, 2026-10-02, 2026-10-09} |

Bentuk tersebut termasuk UNF karena kolom `Buku Dipinjam` menyimpan lebih dari satu kelompok nilai dalam satu sel. Data tersebut belum atomik dan memiliki kelompok data berulang.

### 4.2 First Normal Form (1NF)

Agar memenuhi 1NF, setiap sel harus menyimpan satu nilai atomik dan kelompok data berulang harus dihilangkan.

Hasil perubahan menjadi:

| NIM | Buku ID | Tanggal Pinjam | Nama Mahasiswa | Email | Program Studi | ISBN | Judul | Penerbit ID | Nama Penerbit | Alamat Penerbit | Tahun Terbit | Tanggal Jatuh Tempo | Tanggal Kembali | Status |
|---|---|---|---|---|---|---|---|---|---|---|---|---|---|---|
| D121241001 | B001 | 2026-10-01 | Andi | andi@student.unhas.ac.id | Teknik Informatika | 978001 | Basis Data | P001 | Informatika Press | Makassar | 2025 | 2026-10-08 | NULL | Dipinjam |
| D121241001 | B002 | 2026-10-01 | Andi | andi@student.unhas.ac.id | Teknik Informatika | 978002 | Pemrograman Web | P002 | Media Teknologi | Jakarta | 2026 | 2026-10-08 | NULL | Dipinjam |
| D121241002 | B001 | 2026-10-02 | Rina | rina@student.unhas.ac.id | Teknik Informatika | 978001 | Basis Data | P001 | Informatika Press | Makassar | 2025 | 2026-10-09 | NULL | Dipinjam |

Pada tahap ini setiap kolom sudah memiliki nilai atomik sehingga tabel telah memenuhi 1NF.

Kunci dapat dipandang sebagai kombinasi:

`nim + buku_id + tanggal_pinjam`

Kombinasi tersebut diperlukan karena mahasiswa yang sama dapat meminjam buku yang sama lagi pada waktu yang berbeda.

Walaupun sudah memenuhi 1NF, masih terdapat redundansi. Contohnya, nama dan email mahasiswa ditulis berulang setiap kali mahasiswa melakukan peminjaman. Data buku dan penerbit juga berulang pada setiap transaksi.