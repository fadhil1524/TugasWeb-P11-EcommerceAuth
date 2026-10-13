# Testing Multi-Role (Browser Incognito, 2 akun)

Buka **2 jendela berbeda** (jendela biasa + incognito) supaya session tidak bertabrakan.
Akun demo (hasil seeder, password semuanya `password`):

| Role | Email |
|---|---|
| admin | admin@example.com |
| editor | editor@example.com |
| user | user@example.com |

Isi kolom "Hasil" dan lampirkan screenshot.

| Skenario | Ekspektasi | Hasil |
|---|---|---|
| Belum login buka `/admin`, `/posts` | Redirect ke `/login` | |
| user buka `/admin` | **403** | |
| user buka `/manage/products` | **403** | |
| editor buka `/manage/products` | 200 | |
| editor buka `/admin` | **403** | |
| admin buka `/admin` dan `/manage/products` | 200 | |
| user edit post milik **sendiri** | 200 | |
| user buka `/posts/{id-milik-admin}/edit` (ketik URL manual) | **403** (policy, walau tombol disembunyikan) | |
| editor edit post orang lain | 200 | |
| editor hapus post orang lain | **403** (tombol hapus tidak muncul) | |
| admin hapus post siapa saja | berhasil + flash sukses | |
| admin ubah role user → editor di `/admin` | berhasil; login ulang sebagai user itu, `/manage/products` jadi 200 | |

Tes otomatis (opsional): `php artisan test --filter=RoleAccessTest`
