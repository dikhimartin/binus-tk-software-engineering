[![laravel](https://i.ibb.co/SmtP4vH/image-4.png)](https://laravel.com)

Demo : [https://finalexam.binusassignment.tech](https://finalexam.binusassignment.tech)


## Penjelasan

Saya membuat project ini, karena untuk melengkapi satu tugas UAS  di Universitas. Jadi saya diminta untuk membuat suatu website  dengan kriteria sebagai berikut :

```
Soal Kasus: 
Buatlah Database dengan table berikut ini :

Tabel Room
ID, RoomTypeID, RoomName, Area, Price, Facility

Table RoomType
ID, RoomType

Table Transaction
ID, TransCode, TransDate, CustName, TotalRoomPrice, TotalExtraCharge, FinalTotal

Table DetailTransaction
ID, TransID, RoomID, Days, SubTotalRoom, ExtraCharge


Buatlah dengan Laravel :
1. Authentikasi Login dengan hak akses User dan Admin;

2. CRUD untuk Master Room dan RoomType;

3. CRUD untuk Transaksi dan detailnya, Untuk ExtraCharge terdapat beberapa pilihan yang
  disediakan yaitu :
  A. Minuman soda (20.000)
  B. Air Putih (15.000)
  C. Jasa Laundry (100.000)
  D. Snack (25.000)
  Untuk setiap ExtraCharge berlaku per masing-masing kamar dan dapat terdapat quantity
  disetiap pilihan;
  
4. Hak akses Admin terbatas pada CRUD Master dan Fitur Laporan;

5. Hak akses User dapat memilih Room serta lama menginap dan juga ExtraCharge dari
masing-masing kamar;

6. Fitur Laporan menampilkan List Transaksi disertai dengan filter kategori room dan tanggal
transaksi dari mulai sampai berakhir serta menampilkan semua data yang terdapat pada
tabel transaksi dan detail transaksi;

7. Tambahkan Fitur Grafik untuk melihat hasil booking room berdasarkan periode tertentu
dengan tipe room, TotalRoomPrice, TotalExtraCharge, dan FinalTotal.
```



## Module Aplikasi 

- Dari kriteria tersebut dapat di definisikan beberapa module yang ada dalam website, antara lain :
  
  - Otentikasi dan Otorisasi
  - Registrasi
  - Menu
    - Dashboard
    - Profil
    - Master Data
      - Jenis Kamar
      - Fasilitas
      - Extra Charge
      - Kamar
  
    - Transaksi
    - Laporan
      - Laporan Transaksi
  
    - Akses Pengguna
      - Daftar Pengguna
      - Hak akses Pengguna
  



## Cara menjalankan aplikasi

#### 1. Native Server

Berikut ini rekaman untuk cara menjalankan aplikasinya :

https://asciinema.org/a/586044

Buka browser pada URL  http://localhost:8000, untuk mengakses aplikasi.

- Akses Login :

  | No   | Role        | Username   | Kata sandi |
  | ---- | ----------- | ---------- | ---------- |
  | 1    | Super Admin | superadmin | superadmin |
  | 2    | Admin       | admin      | admin      |
  | 3    | User        | user       | user       |

#### 2. Docker Server

**Tech Stack :**

  - Docker Engine https://docs.docker.com/engine/install
  - Docker Compose https://docs.docker.com/compose/install

**Proses Instalasi :**

- Install Docker Engine & Docker Compose.

- Cloning aplikasi source

  ```shell
  git clone https://github.com/dikhimartin/binus-final-exam-web-developer.git
  ```

- Masuk ke aplikasi source

  ```shell
  cd binus-final-exam-web-developer
  ```

- Copy file environtment

  ```shell
  cp ./project/.env.example ./project/.env
  ```

- Build Dockerfile

  ```shell
  docker build -t myapp .
  ```

- Jalankan aplikasi menggunakan command 

  ```shell
  docker-compose up -d
  ```

- Inisialisasi Database

  ```shell
  docker-compose exec myapp php artisan migrate  
  ```

  ```shell
  docker-compose exec myapp  php artisan db:seed
  ```

- Buka browser pada URL  http://localhost:8000, untuk mengakses aplikasi.

  - Akses Login 
    - Username    : superadmin
    - Password     : superadmin

- Buka browser pada URL  http://localhost:8080, untuk mengakses PHPmyadmin (Database Management).

  - Akses database 
    - server : mysql
    - username : root
    - password : root

- Stop service 

  ```shell
  docker-compose down
  ```

- Stop service with remove volume

  ```shell
  docker-compose down -v
  ```

- Restart service

  ```shell
  docker-compose up -d --force-recreate
  ```

- Akses bash 

  ```shell
  docker-compose exec myapp bash
  ```

  