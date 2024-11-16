## Aksi Bencana Admin Panel & API

Sebelum memulai pastikan sudah menjalankan perintah berikut, serta sesuaikan env dengan yang anda punya, seperti nama database dll.

```bash
  cp .env.example .env
  php artisan key:generate
  composer install
  php artisan migrate:fresh --seed
  php artisan serve
```

## Locations Data

Import data lokasi ke dalam database, file db lokasi dapat anda temukan pada folder `/storage/locations.sql`.

## API Docs

Dokumentasi api dapat diakses pada route berikute:

```bash
  http://{BASE_URL}/docs/api
```