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