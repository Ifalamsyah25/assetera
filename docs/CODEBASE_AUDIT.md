# Codebase Audit

## Ringkasan

Project ini sekarang berfungsi sebagai aplikasi Laravel 13 untuk manajemen aset dengan dua area utama:

- Public/Auth UI: layout tamu kustom di `resources/views/layouts/guest.blade.php`
- Admin UI: panel `AdminLTE` untuk dashboard, assets, users, transactions, maintenances, dan profile

## File Aktif

### Route aktif

- `routes/web.php`
- `routes/auth.php`

### Controller aktif

- `app/Http/Controllers/AssetController.php`
- `app/Http/Controllers/TransactionController.php`
- `app/Http/Controllers/MaintenanceController.php`
- `app/Http/Controllers/UserController.php`
- `app/Http/Controllers/ProfileController.php`
- seluruh controller auth di `app/Http/Controllers/Auth/`

### Model aktif

- `app/Models/User.php`
- `app/Models/Asset.php`
- `app/Models/Transaction.php`
- `app/Models/Maintenance.php`

### View admin aktif

- `resources/views/assets/index.blade.php`
- `resources/views/assets/create.blade.php`
- `resources/views/transactions/index.blade.php`
- `resources/views/transactions/create.blade.php`
- `resources/views/transactions/edit.blade.php`
- `resources/views/maintenances/index.blade.php`
- `resources/views/maintenances/create.blade.php`
- `resources/views/maintenances/edit.blade.php`
- `resources/views/users/index.blade.php`
- `resources/views/users/create.blade.php`
- `resources/views/users/edit.blade.php`
- `resources/views/profile/edit.blade.php`
- `resources/views/profile/partials/update-profile-information-form.blade.php`
- `resources/views/profile/partials/update-password-form.blade.php`
- `resources/views/profile/partials/delete-user-form.blade.php`

### View auth aktif

- `resources/views/layouts/guest.blade.php`
- `resources/views/auth/login.blade.php`
- `resources/views/auth/register.blade.php`
- `resources/views/auth/forgot-password.blade.php`
- `resources/views/auth/reset-password.blade.php`
- `resources/views/auth/verify-email.blade.php`
- `resources/views/auth/confirm-password.blade.php`
- `resources/views/components/auth-session-status.blade.php`

### Konfigurasi inti aktif

- `config/adminlte.php`
- `config/auth.php`
- `config/database.php`
- `config/session.php`
- `config/cache.php`

### Database aktif

- `database/migrations/0001_01_01_000000_create_users_table.php`
- `database/migrations/2026_03_30_144939_create_assets_table.php`
- `database/migrations/2026_03_30_150538_create_maintenances_table.php`
- `database/migrations/2026_03_30_150539_create_transactions_table.php`
- `database/migrations/2026_03_30_150539_add_username_and_role_to_users_table.php`
- `database/seeders/DatabaseSeeder.php`
- `database/factories/UserFactory.php`

## File Sisa Yang Sudah Dibersihkan

Berikut file lama yang sudah tidak jadi jalur aktif dan telah dihapus untuk mengurangi campuran Breeze lama dengan UI AdminLTE sekarang:

- `resources/views/dashboard.blade.php`
- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/navigation.blade.php`
- `app/View/Components/AppLayout.php`
- `resources/views/index.blade.php`
- komponen Breeze lama yang tidak lagi direferensikan:
  - `resources/views/components/application-logo.blade.php`
  - `resources/views/components/danger-button.blade.php`
  - `resources/views/components/dropdown-link.blade.php`
  - `resources/views/components/dropdown.blade.php`
  - `resources/views/components/input-error.blade.php`
  - `resources/views/components/input-label.blade.php`
  - `resources/views/components/layouts/dashboard.blade.php`
  - `resources/views/components/modal.blade.php`
  - `resources/views/components/nav-link.blade.php`
  - `resources/views/components/primary-button.blade.php`
  - `resources/views/components/responsive-nav-link.blade.php`
  - `resources/views/components/secondary-button.blade.php`
  - `resources/views/components/text-input.blade.php`

## Catatan Arsitektur Saat Ini

- Admin panel sepenuhnya memakai `AdminLTE`
- Auth pages memakai layout tamu kustom, bukan lagi halaman Breeze default
- Route `/dashboard` menjadi entry utama admin dan mengarah ke `AssetController@index`
- Logout memiliki dua jalur:
  - `POST /logout` dari Breeze
  - `GET /logout` untuk kompatibilitas menu AdminLTE

## Area Yang Masih Bisa Ditingkatkan

- tambahkan authorization berbasis role untuk memisahkan akses admin, pimpinan, dan staff
- tambahkan test untuk modul `Asset`, `Transaction`, `Maintenance`, dan `User`
- tambah pagination, search, dan filter di halaman index admin
- refactor form berulang menjadi partial reusable bila scope UI bertambah
