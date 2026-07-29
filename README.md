# TrainApp
Sistem administrasi pemesanan tiket kereta berbasis Laravel 12 yang menyediakan pengelolaan pengguna, stasiun, kereta, jadwal, transaksi, voucher, banner promosi, REST API, integrasi chatbot AI, serta deployment menggunakan Docker.


# Panduan Penggunaan dan Menjalankan Aplikasi TrainApp Menggunakan Docker
# 1. Persyaratan Sistem

Sebelum menjalankan aplikasi, pastikan perangkat telah memenuhi persyaratan berikut:

Sistem Operasi Windows 10/11, Linux, atau macOS.
Docker Desktop telah terpasang dan berjalan.
Koneksi internet untuk mengunduh image Docker.
Web browser (Google Chrome, Microsoft Edge, atau Mozilla Firefox).

# 2. Struktur Aplikasi

Aplikasi terdiri dari dua layanan utama:

TrainApp Admin (Laravel)
Berfungsi sebagai backend dan panel admin.
Berjalan pada container Docker.
MySQL
Digunakan sebagai basis data aplikasi.
Berjalan pada container Docker.

# 3. Menjalankan Database MySQL

Jalankan container MySQL dengan perintah berikut:

docker run -d ^
--name trainapp-mysql ^
-e MYSQL_ROOT_PASSWORD=root123 ^
-e MYSQL_DATABASE=trainapp ^
-e MYSQL_USER=trainapp ^
-e MYSQL_PASSWORD=trainapp123 ^
-p 3306:3306 ^
mysql:8.4

Pastikan container berhasil berjalan dengan menjalankan:

docker ps

# 4. Konfigurasi File .env

Pastikan konfigurasi database pada file .env sebagai berikut:

DB_CONNECTION=mysql
DB_HOST=trainapp-mysql
DB_PORT=3306
DB_DATABASE=trainapp
DB_USERNAME=trainapp
DB_PASSWORD=trainapp123

# 5. Build Docker Image

Masuk ke folder proyek:

cd /d A:\trainapp-admin

Build image Docker:

docker build --no-cache -t trainapp-admin:v4 .

Tunggu hingga proses build selesai tanpa error.

#6. Membuat Docker Network

Buat network Docker:

docker network create trainapp-net

Hubungkan container MySQL ke network:

docker network connect trainapp-net trainapp-mysql

# 7. Menjalankan Aplikasi Laravel

Jalankan container aplikasi:

docker run -d ^
--name trainapp-admin-v4 ^
--network trainapp-net ^
-p 8001:80 ^
trainapp-admin:v4

Periksa apakah container telah berjalan:

docker ps

Output yang diharapkan:

0.0.0.0:8001->80/tcp

# 8. Melihat Log Aplikasi

Untuk melihat proses migrasi database dan status aplikasi:

docker logs trainapp-admin-v4

Apabila tidak terdapat pesan error, maka aplikasi telah berhasil dijalankan.

# 9. Mengakses Aplikasi

Buka browser kemudian akses:

http://localhost:8001

Panel administrator dapat diakses melalui:

http://localhost:8001/admin/login

# 10. Menjalankan Ngrok (Opsional)

Apabila aplikasi akan diakses dari perangkat lain atau Android, jalankan:

ngrok http 8001

Ngrok akan menghasilkan URL publik yang dapat digunakan untuk mengakses aplikasi dari luar jaringan lokal.

# 11. Menghentikan Aplikasi

Menghentikan container Laravel:

docker stop trainapp-admin-v4

Menghentikan container MySQL:

docker stop trainapp-mysql

# 12. Menjalankan Kembali Aplikasi

Untuk menjalankan kembali container yang telah dibuat:

docker start trainapp-mysql
docker start trainapp-admin-v4

# 13. Verifikasi

Aplikasi dinyatakan berhasil dijalankan apabila:

Container MySQL berstatus Up.
Container Laravel berstatus Up.
Halaman utama dapat diakses melalui http://localhost:8001.
Panel admin dapat diakses melalui http://localhost:8001/admin/login.
Tidak terdapat error pada log Docker.
