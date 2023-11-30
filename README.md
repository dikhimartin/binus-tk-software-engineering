[![laravel](https://i.ibb.co/SmtP4vH/image-4.png)](https://laravel.com)

------



| Keterangan |                                                              |
| ---------- | ------------------------------------------------------------ |
| Demo       | [https://softwareengineering.binusassignment.tech/](https://softwareengineering.binusassignment.tech/) |
| Repository | https://github.com/dikhimartin/binus-tk-software-engineering |

## Navigasi :

- [Navigasi :](#navigasi-)
- [Penjelasan :](#penjelasan-)
- [Laporan Pengerjaan :](#laporan-pengerjaan-)
- [Module Aplikasi :](#module-aplikasi-)
- [Cara menjalankan aplikasi :](#cara-menjalankan-aplikasi-)
  - [1. Native Server](#1-native-server)
  - [2. Docker Server](#2-docker-server)

## Penjelasan :

Kami membuat proyek ini untuk melengkapi tugas mata kuliah **COMP6804036 - Software Engineering**. 
kemudian dalam soal kasus diminta untuk membuat sebuah website dengan kriteria sebagai berikut:

```
Soal Kasus: 
Buatlah sebuah aplikasi software dan dokumentasikan dalam sebuah laporan. Laporan dibuat dalam satu dokumen berkelanjutan untuk Tugas kelompok 1,2,3, dan 4!
Tugas sebagai berikut:
    1. Project, usulkan suatu project software dengan topik tertentu (bebas) berbasis web/mobile.
    2. Tentukan software proses model yang akan diterapkan dari proyek yang diusulkan dan jelaskan alasannya!
    3. Lakukan Analisa masalah terdiri dari:
      a. Analisa sistem berjalan/aplikasi sejenis!
      b. Analisa kebutuhan!
      c. Usulan Permasalahan!
```

## Laporan Pengerjaan : 

![](https://i.imgur.com/6NWMIpP.jpeg)



## Module Aplikasi :

- Dari kriteria tersebut dapat di definisikan beberapa module yang ada dalam website, antara lain :

  


  - ### **Otentikasi dan Otorisasi**

    - #### **Login** 

      ![](https://i.imgur.com/dyPw8ZY.png)

    - #### **Registrasi**

      ![](https://i.imgur.com/6FL2n6c.png)

  - ### **Menu**

    - #### **Dashboard**

      ![](https://i.imgur.com/ZVQmxGU.png)

      Demo : 
      Registrasi dan Reservasi Kamar : https://jam.dev/c/8a96664a-88e2-4dbd-b833-4aa4425cd0b1

    - #### **Profil**

      ![](https://i.imgur.com/uf4k7WS.png)

      

    - #### **Master Data**

      - ##### **Jenis Kamar**

        ![](https://i.imgur.com/gbFeSIp.png)

        

      - ##### **Fasilitas**

        ![](https://i.imgur.com/d02uMsZ.png)

        

      - ##### **Extra Charge**

        ![](https://i.imgur.com/hXX2Ifl.png)

        

      - ##### **Kamar**

        ![](https://i.imgur.com/3O5YnZR.png)

        Demo : 
        Master Kamar : https://jam.dev/c/adecb312-c3ec-4c50-a08e-88c86213ffa3

    - #### **Transaksi**

      ![](https://i.imgur.com/cGoOQjD.png)

    - #### **Laporan**

      - ##### **Laporan Transaksi**

        ![](https://i.imgur.com/JqQDamz.png)

        Demo : 
        Laporan : https://jam.dev/c/78e435ef-c03c-4fe5-9a7c-3ca53b8d2187

    - #### **Akses Pengguna**

      - ##### **Daftar Pengguna**

        ![](https://i.imgur.com/sQRGV0h.png)

        

      - ##### **Hak akses Pengguna**

        ![](https://i.imgur.com/Nyo0Ulf.png)



## Cara menjalankan aplikasi :

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
  git clone https://github.com/dikhimartin/binus-tk-software-engineering
  ```

- Masuk ke aplikasi source

  ```shell
  cd binus-tk-software-engineering
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

  