# Panduan Tugas Rutin 11

1. Buat project, database, dan `.env` (lihat README).
2. `composer require laravel/breeze --dev` → `php artisan breeze:install blade` → `php artisan migrate`. Screenshot halaman login/register.
3. Jalankan `make:model`, `make:migration`, `make:policy PostPolicy --model=Post`, `make:middleware EnsureRole` minimal sekali (bukti terminal), lalu **timpa** dengan file dari zip.
4. Salin semua file dari zip. Muhammad Fadhil, `4253550018`, `PSIK25A` di README.
5. `php artisan migrate:fresh --seed`, `npm install && npm run dev`, `php artisan serve`.
6. `php artisan tinker` → jalankan 5 query di `TINKER.md`, screenshot.
7. Uji 2 role (incognito) memakai `TESTING_ROLE.md`; isi kolom Hasil.
8. (Opsional) `php artisan test --filter=RoleAccessTest`.
9. Push ke GitHub repo **TugasWeb-P11-EcommerceAuth**; pastikan `.env` tidak ikut.
