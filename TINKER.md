# Dokumentasi 5 Query Tinker

Jalankan `php artisan migrate:fresh --seed`, lalu `php artisan tinker`.
**Screenshot** output tiap query dan tempel di bawah (folder `screenshots/`).

| # | Query | Tujuan |
|---|---|---|
| 1 | `Product::count()` | Jumlah produk hasil seeder (harus ≥ 50) |
| 2 | `Product::with('category')->first()` | Relasi belongsTo + eager loading |
| 3 | `User::has('orders')->first()->orders->count()` | Relasi hasMany (jumlah order seorang pelanggan) |
| 4 | `Order::withSum('items as total_qty', 'quantity')->first()` | Agregasi relasi (total qty per order) |
| 5 | `Product::whereRelation('category', 'name', 'Elektronik')->active()->inStock()->get(['id','name','price','stock'])` | Filter lewat relasi + 2 local scope |

Tambahan (bonus eager loading — bandingkan jumlah query):
```php
DB::enableQueryLog();
Product::all()->each(fn ($p) => $p->category->name);     // lazy: 1 + N query
count(DB::getQueryLog());                                 // contoh: 58
DB::flushQueryLog();
Product::with('category')->get()->each(fn ($p) => $p->category->name); // eager: 2 query
count(DB::getQueryLog());                                 // 2
```
Opsional: total pendapatan — `App\Models\OrderItem::selectRaw('SUM(quantity * price) AS revenue')->value('revenue')`
