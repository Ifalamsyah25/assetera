## 🚀 Cara Menjalankan Project (Laravel)

1. Clone repository
   git clone https://github.com/Ifalamsyah25/assetera.git

2. Masuk ke folder project
   cd assetera

3. Install dependency
   composer install

4. Copy file environment
   cp .env.example .env

5. Generate key aplikasi
   php artisan key:generate

6. Konfigurasi database di file .env
   DB_DATABASE=assetera
   DB_USERNAME=root
   DB_PASSWORD=

7. Jalankan migrasi database
   php artisan migrate

8. Jalankan server Laravel
   php artisan serve

9. Akses aplikasi di browser
   http://127.0.0.1:8000