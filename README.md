## Aksi Bencana API

Sebelum memulai pastikan sudah menjalankan perintah berikut, serta sesuaikan env dengan yang anda punya, seperti nama database dll.

```bash
  cp .env.example .env
  php artisan key:generate
  composer install
  php artisan migrate:fresh --seed
  php artisan serve
```