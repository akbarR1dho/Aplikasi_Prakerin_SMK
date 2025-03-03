<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Deskripsi Proyek

Aplikasi yang membantu dalam proses prakerin

## Persyaratan Sistem

-   PHP >= 8.2
-   Composer
-   Node JS >= 20.x
-   Npm / Yarn
-   Postgresql 14

## Setup

### 1. Install Dependensi PHP

Composer :

```bash
composer install
```

### 2. Install Depensi Javascript

Npm :

```bash
npm install
```

Yarn :

```bash
yarn install
```

### 3. Buat File .env

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Buat Database

Buat database dengan nama prakerin_smk

### 6. Jalankan Migrasi Database

Jalankan perintah migrasi untuk generate tabel database

```bash
php artisan migrate
```

### 7. Jalankan Seeder Database

Jalankan perintah seeder untuk generate data awal yang diperlukan

```bash
php artisan db:seed
```
