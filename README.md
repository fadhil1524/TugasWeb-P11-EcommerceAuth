# TugasWeb-P11-EcommerceAuth

- **Nama:** Muhammad Fadhil
- **NIM:** 4253550018
- **Kelas:** 4253550018

Database e-commerce (7 tabel) dengan Eloquent + autentikasi Laravel Breeze, multi-role (admin / editor / user), custom middleware, dan PostPolicy.

## Instalasi (urutan penting)

```bash
composer create-project laravel/laravel TugasWeb-P11-EcommerceAuth
cd TugasWeb-P11-EcommerceAuth
# buat database di phpMyAdmin (mis. p11_ecommerce) lalu atur .env (DB_CONNECTION=mysql, DB_DATABASE=...)

# 1) Pasang Breeze DULU
composer require laravel/breeze --dev
php artisan breeze:install blade

# 2) Baru salin file repo ini ke project (menimpa bootstrap/app.php,
#    routes/web.php, resources/views/dashboard.blade.php, app/Models/User.php)

# 3) Migrate + seed, lalu jalankan
php artisan migrate:fresh --seed
npm install && npm run dev      # terminal 1
php artisan serve               # terminal 2
```

Akun demo (password: `password`): `admin@example.com`, `editor@example.com`, `user@example.com`.

> Akun & password demo ini hanya untuk development lokal, jangan dipakai di produksi.

## Bagian A — Database & Eloquent

| # | Requirement                                | Lokasi                                                                                                                                                                                                            |
| - | ------------------------------------------ | ----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 1 | Migrations 7 tabel + FK constraints        | `database/migrations/*` — `users`, `categories`, `products`, `orders`, `order_items`, `tags`, `product_tag` (pivot). Tambahan: kolom `role` di `users` dan tabel `posts` untuk demo policy |
| 2 | Seeders + factories (50+ produk realistis) | `database/seeders/DatabaseSeeder.php` (57 produk, 7 kategori), `database/factories/*`                                                                                                                         |
| 3 | Model + relationships + ≥ 1 scope         | `app/Models/*` — `belongsTo`, `hasMany`, `belongsToMany`; scope `active()` & `inStock()` di `Product`                                                                                              |
| 4 | Dokumentasi 5 query Tinker                 | [`TINKER.md`](TINKER.md)                                                                                                                                                                                         |

Relasi: Category 1—N Product · User 1—N Product (penjual) · User 1—N Order · Order 1—N OrderItem · Product 1—N OrderItem · Product N—N Tag.

## Bagian B — Auth & Security

| # | Requirement                                 | Lokasi                                                                                                                |
| - | ------------------------------------------- | --------------------------------------------------------------------------------------------------------------------- |
| 5 | Breeze (login/register/logout)              | `composer require laravel/breeze --dev` + `breeze:install blade`                                                  |
| 6 | Multi-role + custom middleware              | kolom`users.role`; `app/Http/Middleware/EnsureRole.php`, alias `role` di `bootstrap/app.php`                  |
| 7 | PostPolicy edit/delete                      | `app/Policies/PostPolicy.php` + `Gate::authorize()` di `PostController`                                         |
| 8 | Route protection + testing incognito 2 role | `routes/web.php`; hasil di [`TESTING_ROLE.md`](TESTING_ROLE.md); tes otomatis `tests/Feature/RoleAccessTest.php` |

Hak akses:

| Role   | Posts                      | Kelola produk (`/manage/products`) | Panel admin (`/admin`) |
| ------ | -------------------------- | ------------------------------------ | ------------------------ |
| admin  | edit & hapus semua         | ✅                                   | ✅                       |
| editor | edit semua, hapus miliknya | ✅                                   | ❌ 403                   |
| user   | edit & hapus miliknya      | ❌ 403                               | ❌ 403                   |

Keamanan: `role` tidak masuk `$fillable` (anti mass assignment), otorisasi dicek di controller/policy (bukan hanya menyembunyikan tombol), `@csrf` di semua form.

## Bonus

- **Eager loading:** `ProductController@index` (`with(['category','tags'])->withCount()`) + demo hitung query di `TINKER.md`.
- **Filament admin panel** (opsional):

```bash
composer require filament/filament
php artisan filament:install --panels
php artisan make:filament-user
```
