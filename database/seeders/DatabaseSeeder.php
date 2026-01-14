<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin
        User::create([
            'name' => 'Admin Bengawan',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->command->info('User Admin berhasil dibuat: admin@gmail.com / password');

        // 2. Buat Kategori
        $categories = [
            ['name' => 'Laptop Gaming', 'slug' => 'laptop-gaming'],
            ['name' => 'Laptop Ultrabook', 'slug' => 'laptop-ultrabook'],
            ['name' => 'Laptop 2-in-1', 'slug' => 'laptop-2-in-1'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }

        // Ambil ID kategori untuk relasi
        $kategoriGaming = Category::where('slug', 'laptop-gaming')->first();
        $kategoriUltrabook = Category::where('slug', 'laptop-ultrabook')->first();
        $kategori2in1 = Category::where('slug', 'laptop-2-in-1')->first();

        // 3. Daftar Produk (Array agar lebih rapi)
        $products = [
            [
                'category_id' => $kategoriGaming->id,
                'name' => 'ASUS ROG Strix G15',
                'description' => 'Laptop gaming dengan performa tinggi, dilengkapi dengan prosesor Intel i7 dan kartu grafis NVIDIA GeForce RTX 3060.',
                'price' => 15000000.00,
                'discount_price' => 14000000.00,
                'stock' => 10,
                'link_shopee' => 'https://shopee.co.id/dummy-rog',
                'link_tokopedia' => 'https://tokopedia.com/dummy-rog',
                'is_active' => true,
            ],
            [
                'category_id' => $kategoriGaming->id,
                'name' => 'MSI GE66 Raider',
                'description' => 'Laptop gaming MSI dengan spesifikasi tinggi, dilengkapi dengan prosesor Intel Core i9 dan NVIDIA GeForce RTX 3070.',
                'price' => 25000000.00,
                'discount_price' => null,
                'stock' => 0, // Out of stock
                'link_shopee' => 'https://shopee.co.id/dummy-msi',
                'link_tokopedia' => 'https://tokopedia.com/dummy-msi',
                'is_active' => true,
            ],
            [
                'category_id' => $kategoriGaming->id,
                'name' => 'Acer Nitro 5 AN515',
                'description' => 'Laptop gaming entry-level terbaik dengan pendinginan dual fan, Ryzen 5000 Series dan RTX 3050.',
                'price' => 12500000.00,
                'discount_price' => 11999000.00,
                'stock' => 25,
                'link_shopee' => 'https://shopee.co.id/dummy-acer',
                'link_tokopedia' => null,
                'is_active' => true,
            ],
            [
                'category_id' => $kategoriGaming->id,
                'name' => 'Lenovo Legion 5 Pro',
                'description' => 'Layar 16 inci QHD 165Hz, Ryzen 7 5800H, dan build quality yang sangat kokoh untuk gamer profesional.',
                'price' => 21000000.00,
                'discount_price' => null,
                'stock' => 8,
                'link_shopee' => null,
                'link_tokopedia' => 'https://tokopedia.com/dummy-legion',
                'is_active' => true,
            ],
            [
                'category_id' => $kategoriGaming->id,
                'name' => 'Razer Blade 15 Advanced',
                'description' => 'Laptop gaming tipis namun sangat bertenaga dengan estetika minimalis premium.',
                'price' => 35000000.00,
                'discount_price' => 33500000.00,
                'stock' => 3,
                'link_shopee' => 'https://shopee.co.id/dummy-razer',
                'link_tokopedia' => 'https://tokopedia.com/dummy-razer',
                'is_active' => true,
            ],

            // --- KATEGORI ULTRABOOK ---
            [
                'category_id' => $kategoriUltrabook->id,
                'name' => 'HP Spectre x360 14',
                'description' => 'Laptop ultrabook premium dengan desain elegan, prosesor Intel Core i7, dan layar sentuh 13.5 inci.',
                'price' => 20000000.00,
                'discount_price' => null,
                'stock' => 5,
                'link_shopee' => 'https://shopee.co.id/dummy-spectre',
                'link_tokopedia' => null,
                'is_active' => true,
            ],
            [
                'category_id' => $kategoriUltrabook->id,
                'name' => 'MacBook Air M2 (Midnight)',
                'description' => 'Chip Apple M2 super cepat, desain fanless yang hening, dan baterai tahan hingga 18 jam.',
                'price' => 18999000.00,
                'discount_price' => 17499000.00,
                'stock' => 12,
                'link_shopee' => 'https://shopee.co.id/dummy-macbook',
                'link_tokopedia' => 'https://tokopedia.com/dummy-macbook',
                'is_active' => true,
            ],
            [
                'category_id' => $kategoriUltrabook->id,
                'name' => 'Dell XPS 13 Plus',
                'description' => 'Desain futuristik dengan touch bar kapasitif dan layar OLED InfinityEdge yang memukau.',
                'price' => 28000000.00,
                'discount_price' => null,
                'stock' => 4,
                'link_shopee' => null,
                'link_tokopedia' => 'https://tokopedia.com/dummy-xps',
                'is_active' => true,
            ],
            [
                'category_id' => $kategoriUltrabook->id,
                'name' => 'Asus Zenbook S 13 OLED',
                'description' => 'Laptop OLED 13.3 inci paling tipis dan ringan di dunia, bobot hanya 1kg.',
                'price' => 17500000.00,
                'discount_price' => null,
                'stock' => 7,
                'link_shopee' => 'https://shopee.co.id/dummy-zenbook',
                'link_tokopedia' => 'https://tokopedia.com/dummy-zenbook',
                'is_active' => true,
            ],
            [
                'category_id' => $kategoriUltrabook->id,
                'name' => 'MacBook Air 2019 (Arsip)',
                'description' => 'Laptop lama dengan performa terbatas, sudah tidak diproduksi lagi.',
                'price' => 15000000.00,
                'discount_price' => null,
                'stock' => 3,
                'link_shopee' => null,
                'link_tokopedia' => null,
                'is_active' => false, // Non-aktif
            ],

            // --- KATEGORI 2-IN-1 ---
            [
                'category_id' => $kategori2in1->id,
                'name' => 'Lenovo Yoga 7i',
                'description' => 'Laptop 2-in-1 convertible dengan layar sentuh yang dapat diputar 360 derajat, Intel Evo certified.',
                'price' => 13000000.00,
                'discount_price' => null,
                'stock' => 15,
                'link_shopee' => null,
                'link_tokopedia' => 'https://tokopedia.com/dummy-yoga',
                'is_active' => true,
            ],
            [
                'category_id' => $kategori2in1->id,
                'name' => 'Microsoft Surface Pro 9',
                'description' => 'Fleksibilitas tablet dengan performa laptop. Layar PixelSense 13 inci dan kickstand ikonik.',
                'price' => 19500000.00,
                'discount_price' => null,
                'stock' => 6,
                'link_shopee' => 'https://shopee.co.id/dummy-surface',
                'link_tokopedia' => 'https://tokopedia.com/dummy-surface',
                'is_active' => true,
            ],
            [
                'category_id' => $kategori2in1->id,
                'name' => 'HP Envy x360',
                'description' => 'Laptop konvertibel AMD Ryzen yang terjangkau dengan dukungan stylus pen.',
                'price' => 14500000.00,
                'discount_price' => 13500000.00,
                'stock' => 9,
                'link_shopee' => 'https://shopee.co.id/dummy-envy',
                'link_tokopedia' => null,
                'is_active' => true,
            ],
            [
                'category_id' => $kategori2in1->id,
                'name' => 'Asus Vivobook Flip 14',
                'description' => 'Pilihan hemat untuk pelajar yang membutuhkan laptop layar sentuh fleksibel.',
                'price' => 8500000.00,
                'discount_price' => null,
                'stock' => 20,
                'link_shopee' => 'https://shopee.co.id/dummy-flip',
                'link_tokopedia' => 'https://tokopedia.com/dummy-flip',
                'is_active' => true,
            ],
        ];

        // 4. Eksekusi pembuatan data produk
        foreach ($products as $product) {
            Product::create([
                'category_id' => $product['category_id'],
                'name' => $product['name'],
                'slug' => Str::slug($product['name']), // Otomatis buat slug dari nama
                'image' => null,
                'description' => $product['description'],
                'price' => $product['price'],
                'discount_price' => $product['discount_price'],
                'stock' => $product['stock'],
                'link_shopee' => $product['link_shopee'],
                'link_tokopedia' => $product['link_tokopedia'],
                'is_active' => $product['is_active'],
            ]);
        }
        $this->call([
            SettingSeeder::class,
        ]);
        $this->command->info('Dummy data Categories dan Products berhasil dibuat (Total: ' . count($products) . ' produk).');
    }
}
