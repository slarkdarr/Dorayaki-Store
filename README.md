# World Break Down Web App

## Description
Sebuah aplikasi e-commerce sederhana menjual varian dorayaki

## Prerequisites
- nginx / apache
- php 8.x
- sqlite php extension enabled
- php pdo
- php soap extension enabled

## Menjalankan server
- Jalankan nginx atau apache
- Jalankan perintah berikut pada root directory, port 8080 dapat diganti sesuai kondisi
`php -S localhost:8080`
- Pada browser buka `localhost:8080` atau port yang sesuai

## Menjalankan docker
- Pada working directory jalankan `docker compose up -d` dan buka pada browser `localhost:8000`

## Keterangan tambahan

### File tambahan (diluar aplikasi / sebagai developer)
- seeder.php digunakan untuk menginput data dummy
- database/initDb.php digunakan untuk melakukan drop tables kemudian input tables (users, products, histories). Ini juga melakukan input data admin dan user dummy.
- Berikut akun yang dapat digunakan diawal :
    * username : admin  password : admin123
    * username : user password : user123 