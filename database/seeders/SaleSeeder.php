<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->error('Tidak ada data produk. Harap input/seed produk terlebih dahulu!');
            return;
        }

        $this->command->info('Sedang men-generate data penjualan dummy...');


        foreach (range(1, 250) as $index) {
            $product = $products->random();



            $transactionDate = Carbon::today()->subDays(rand(0, 90));



            $qty = $faker->randomElement([1, 1, 1, 1, 1, 2, 2, 3]);


            $sellingPrice = $product->price;




            $baseCost = $product->cost_price > 0
                ? $product->cost_price
                : $sellingPrice * $faker->randomFloat(2, 0.70, 0.90);


            $totalProfit = ($sellingPrice - $baseCost) * $qty;

            Sale::create([
                'product_id' => $product->id,
                'quantity' => $qty,
                'cost_price' => $baseCost,
                'selling_price' => $sellingPrice,
                'total_profit' => $totalProfit,
                'transaction_date' => $transactionDate,
                'created_at' => $transactionDate,
                'updated_at' => $transactionDate,
            ]);
        }

        $this->command->info('Berhasil membuat 250 data transaksi dummy!');
    }
}
