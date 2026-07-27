# Skenario Functional Testing (Pengujian Fungsional) Sistem E-Commerce

Dokumen ini berisi daftar skenario pengujian fungsional untuk menguji fitur-fitur dari sistem e-commerce.

## 1. Modul Autentikasi & Akun
| ID | Fitur | Skenario Pengujian | Langkah - langkah | Hasil yang Diharapkan | Status |
|----|---|---|---|---|---|
| AUTH-01 | Register | Mendaftar akun dengan data yang valid. | 1. Buka `register.php`<br>2. Isi nama, email, dan password yang valid.<br>3. Klik tombol Daftar. | Akun berhasil dibuat, sistem menyimpan ke database dan diarahkan ke halaman login. | [ ] |
| AUTH-02 | Register | Mendaftar dengan email yang sudah terdaftar. | 1. Buka `register.php`<br>2. Isi form dengan email yang sudah ada.<br>3. Klik tombol Daftar. | Sistem menolak pendaftaran dan memunculkan notifikasi email sudah digunakan. | [ ] |
| AUTH-03 | Login | Login menggunakan email dan password valid. | 1. Buka `login.php`<br>2. Input email dan password benar.<br>3. Klik Login. | Login berhasil, session dibuat, pengguna diarahkan ke halaman `index.php`. | [ ] |
| AUTH-04 | Login | Login dengan password yang salah. | 1. Buka `login.php`<br>2. Input email benar dan password salah.<br>3. Klik Login. | Login gagal dan memunculkan pesan error "Email atau Password salah". | [ ] |
| AUTH-05 | Logout | Mengakhiri sesi pengguna. | 1. Saat posisi login, klik tombol Logout. | Sesi dihapus, pengguna diarahkan kembali ke `login.php` atau `index.php` (tanpa status login). | [ ] |
| AUTH-06 | Ubah Password | Mengganti password pengguna yang sedang login. | 1. Login lalu buka `ubah_password.php`<br>2. Isi password lama dan password baru.<br>3. Klik Simpan. | Password diperbarui di database, muncul notifikasi sukses. | [ ] |

## 2. Modul Katalog Produk
| ID | Fitur | Skenario Pengujian | Langkah - langkah | Hasil yang Diharapkan | Status |
|----|---|---|---|---|---|
| PROD-01 | Tampil Produk | Melihat daftar produk di beranda. | 1. Buka halaman `index.php`. | Sistem berhasil memuat dan menampilkan produk-produk dari database. | [ ] |
| PROD-02 | Detail Produk | Melihat informasi detail suatu produk. | 1. Pada `index.php`, klik salah satu produk. | Halaman diarahkan ke `detail_produk.php` dan menampilkan rincian (foto, harga, deskripsi) dengan benar. | [ ] |

## 3. Modul Keranjang Belanja
| ID | Fitur | Skenario Pengujian | Langkah - langkah | Hasil yang Diharapkan | Status |
|----|---|---|---|---|---|
| CART-01 | Tambah | Memasukkan produk ke keranjang. | 1. Buka `detail_produk.php`<br>2. Klik tombol "Tambah ke Keranjang". | Produk dan jumlahnya berhasil masuk ke `keranjang.php`, notifikasi sukses muncul. | [ ] |
| CART-02 | View | Melihat isi keranjang belanja. | 1. Klik ikon keranjang (Buka `keranjang.php`). | Menampilkan daftar produk yang ditambahkan beserta total harga (subtotal). | [ ] |
| CART-03 | Ubah Kuantitas | Mengubah jumlah produk di dalam keranjang. | 1. Di `keranjang.php`, ubah jumlah/kuantitas item.<br>2. Klik tombol Perbarui/Update. | Subtotal dan Total akhir otomatis diperbarui sesuai perhitungan yang benar. | [ ] |
| CART-04 | Hapus | Menghapus item dari keranjang. | 1. Di `keranjang.php`, klik tombol Hapus pada salah satu produk. | Item hilang dari keranjang dan Total akhir otomatis diperbarui. | [ ] |

## 4. Modul Checkout & Pembayaran
| ID | Fitur | Skenario Pengujian | Langkah - langkah | Hasil yang Diharapkan | Status |
|----|---|---|---|---|---|
| CHK-01 | Alamat | Menambah alamat pengiriman baru. | 1. Buka `alamat.php`<br>2. Isi data alamat lengkap, klik Simpan. | Alamat berhasil tersimpan dan bisa dipilih saat checkout. | [ ] |
| CHK-02 | Checkout | Melakukan proses checkout dengan valid. | 1. Dari keranjang, klik tombol Checkout.<br>2. Pilih alamat dan opsi pengiriman.<br>3. Klik Buat Pesanan. | Pesanan terbuat di sistem, diarahkan ke metode pembayaran, stok produk di database terkurangi. | [ ] |
| CHK-03 | Webhook | Verifikasi status pembayaran. | 1. Selesaikan pembayaran dari Payment Gateway.<br>2. Tunggu `webhook.php` memproses respons. | Status pesanan di database otomatis berubah dari "Menunggu Pembayaran" menjadi "Dibayar/Diproses". | [ ] |

## 5. Modul Riwayat & Manajemen Pesanan (User)
| ID | Fitur | Skenario Pengujian | Langkah - langkah | Hasil yang Diharapkan | Status |
|----|---|---|---|---|---|
| ORD-01 | Riwayat | Melihat riwayat seluruh pesanan. | 1. Buka `riwayat.php`. | Menampilkan list transaksi yang pernah dilakukan oleh user lengkap dengan status terbaru. | [ ] |
| ORD-02 | Detail Order | Melihat rincian 1 pesanan. | 1. Di halaman riwayat, klik tombol Detail pada salah satu pesanan. | Halaman `detail_pesanan.php` menampilkan rincian produk, alamat, dan total biaya. | [ ] |
| ORD-03 | Selesai | Mengonfirmasi pesanan diterima. | 1. Buka halaman pesanan yang sedang dikirim.<br>2. Klik tombol "Pesanan Diterima". | Status transaksi berubah menjadi "Selesai" dan aksi terekam di database. | [ ] |
