<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /** Katalog realistis: kategori => [[nama produk, harga Rupiah], ...] (57 produk) */
    private const CATALOG = [
        'Elektronik' => [
            ['Earbuds Bluetooth TWS', 249000], ['Powerbank 10000mAh', 189000],
            ['Mouse Wireless 2.4GHz', 115000], ['Keyboard Mekanik Blue Switch', 459000],
            ['Flashdisk 64GB USB 3.0', 79000], ['Kabel USB-C Fast Charging 1m', 45000],
            ['Webcam Full HD 1080p', 349000], ['Speaker Bluetooth Portable', 299000],
            ['Smartband Fitness Tracker', 399000],
        ],
        'Fashion Pria' => [
            ['Kaos Polos Cotton Combed 30s', 65000], ['Kemeja Flannel Kotak', 149000],
            ['Celana Chino Slim Fit', 189000], ['Hoodie Zipper Fleece', 225000],
            ['Jaket Bomber Waterproof', 275000], ['Sepatu Sneakers Canvas', 199000],
            ['Topi Baseball Polos', 55000], ['Ikat Pinggang Kulit Asli', 95000],
        ],
        'Fashion Wanita' => [
            ['Blouse Katun Rayon', 125000], ['Gamis Syari Polos', 239000],
            ['Hijab Pashmina Ceruty', 45000], ['Rok Plisket Panjang', 99000],
            ['Tas Selempang Wanita', 165000], ['Flat Shoes Wanita', 135000],
            ['Cardigan Rajut Oversize', 145000], ['Tunik Batik Modern', 175000],
        ],
        'Peralatan Rumah' => [
            ['Rice Cooker 1.8 Liter', 349000], ['Blender Kaca 2 Liter', 279000],
            ['Set Panci Anti Lengket 5 Pcs', 399000], ['Lampu LED Bulb 12W (isi 4)', 69000],
            ['Kipas Angin Berdiri 16 Inci', 289000], ['Dispenser Air Galon Bawah', 549000],
            ['Sapu & Pel Lantai Praktis', 85000], ['Rak Sepatu Susun 4 Tingkat', 119000],
        ],
        'Buku & Alat Tulis' => [
            ['Buku Tulis 38 Lembar (isi 10)', 45000], ['Pulpen Gel 0.5mm (isi 12)', 36000],
            ['Buku Panduan Laravel untuk Pemula', 95000], ['Buku Dasar Pemrograman PHP', 89000],
            ['Stabilo Highlighter 6 Warna', 42000], ['Kalkulator Scientific 240 Fungsi', 129000],
            ['Map Plastik Snap (isi 12)', 28000], ['Notebook A5 Hardcover Dotted', 59000],
        ],
        'Olahraga' => [
            ['Matras Yoga 6mm', 95000], ['Dumbbell Vinyl 5kg (sepasang)', 175000],
            ['Sepatu Lari Ringan', 359000], ['Raket Badminton Karbon (set 2)', 279000],
            ['Bola Futsal Ukuran 4', 149000], ['Botol Minum Tumbler 1 Liter', 69000],
            ['Tali Skipping Speed Bearing', 49000], ['Sarung Tangan Gym', 65000],
        ],
        'Makanan & Minuman' => [
            ['Kopi Arabika Gayo 250g', 85000], ['Teh Hijau Celup (isi 50)', 39000],
            ['Madu Hutan Murni 500ml', 129000], ['Keripik Singkong Balado 250g', 28000],
            ['Bika Ambon Medan', 65000], ['Sirup Markisa 750ml', 42000],
            ['Teri Medan Kacang 200g', 38000], ['Cokelat Batang Dark 70%', 55000],
        ],
    ];

    public function run(): void
    {
        // ---- Akun demo (password semua: "password" dari UserFactory) ----
        // Factory membuat model dalam mode unguarded, jadi 'role' bisa diisi di sini.
        $admin  = User::factory()->create(['name' => 'Admin Toko',    'email' => 'admin@example.com',  'role' => 'admin']);
        $editor = User::factory()->create(['name' => 'Editor Konten', 'email' => 'editor@example.com', 'role' => 'editor']);
        $user   = User::factory()->create(['name' => 'User Biasa',    'email' => 'user@example.com',   'role' => 'user']);
        $customers = User::factory()->count(7)->create(); // role default: user

        // ---- Tag ----
        $tags = collect(['terlaris', 'baru', 'diskon', 'premium', 'hemat', 'impor', 'lokal'])
            ->map(fn ($name) => Tag::create(['name' => $name]));

        // ---- Kategori + produk (50+) ----
        foreach (self::CATALOG as $categoryName => $items) {
            $category = Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            foreach ($items as [$name, $price]) {
                $product = Product::factory()->create([
                    'category_id' => $category->id,
                    'user_id'     => $admin->id,
                    'name'        => $name,
                    'slug'        => Str::slug($name),
                    'price'       => $price,
                    'stock'       => fake()->numberBetween(5, 150),
                    'is_active'   => true,
                ]);

                $product->tags()->attach($tags->random(fake()->numberBetween(1, 3))->pluck('id'));
            }
        }

        // ---- Order + order_items ----
        for ($i = 0; $i < 25; $i++) {
            $order = Order::factory()->create(['user_id' => $customers->random()->id]);

            Product::inRandomOrder()
                ->take(fake()->numberBetween(1, 4))
                ->get()
                ->each(fn (Product $p) => $order->items()->create([
                    'product_id' => $p->id,
                    'quantity'   => fake()->numberBetween(1, 3),
                    'price'      => $p->price, // snapshot harga saat transaksi
                ]));
        }

        // ---- Post (untuk demo PostPolicy) ----
        Post::factory()->count(3)->create(['user_id' => $admin->id]);
        Post::factory()->count(3)->create(['user_id' => $editor->id]);
        Post::factory()->count(3)->create(['user_id' => $user->id]);
    }
}
