<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing products to prevent duplicates
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        Product::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $products = [
            [
                'name' => 'Black T-shirt',
                'image' => 'p-1.png',
                'price' => 80.00,
                'stock' => 486,
                'sold' => 155,
                'category' => 'Fashion',
                'rating' => 4.5,
                'reviews_count' => 55,
                'size' => 'S , M , L , Xl'
            ],
            [
                'name' => 'Olive Green Leather Bag',
                'image' => 'p-2.png',
                'price' => 136.00,
                'stock' => 784,
                'sold' => 674,
                'category' => 'Hand Bag',
                'rating' => 4.1,
                'reviews_count' => 143,
                'size' => 'S , M'
            ],
            [
                'name' => 'Women Golden Dress',
                'image' => 'p-3.png',
                'price' => 219.00,
                'stock' => 769,
                'sold' => 180,
                'category' => 'Fashion',
                'rating' => 4.4,
                'reviews_count' => 174,
                'size' => 'S , M'
            ],
            [
                'name' => 'Gray Cap For Men',
                'image' => 'p-4.png',
                'price' => 76.00,
                'stock' => 571,
                'sold' => 87,
                'category' => 'Cap',
                'rating' => 4.2,
                'reviews_count' => 23,
                'size' => 'S , M , L'
            ],
            [
                'name' => 'Dark Green Cargo Pent',
                'image' => 'p-5.png',
                'price' => 110.00,
                'stock' => 241,
                'sold' => 342,
                'category' => 'Fashion',
                'rating' => 4.4,
                'reviews_count' => 109,
                'size' => 'S , M , L , Xl'
            ],
            [
                'name' => 'Orange Multi Color Headphone',
                'image' => 'p-6.png',
                'price' => 231.00,
                'stock' => 821,
                'sold' => 231,
                'category' => 'Electronics',
                'rating' => 4.2,
                'reviews_count' => 200,
                'size' => 'S , M'
            ],
            [
                'name' => 'Kid\'s Yellow Shoes',
                'image' => 'p-7.png',
                'price' => 89.00,
                'stock' => 321,
                'sold' => 681,
                'category' => 'Shoes',
                'rating' => 4.5,
                'reviews_count' => 321,
                'size' => '18 , 19 , 20 , 21'
            ],
            [
                'name' => 'Men Dark Brown Wallet',
                'image' => 'p-8.png',
                'price' => 132.00,
                'stock' => 190,
                'sold' => 212,
                'category' => 'Wallet',
                'rating' => 4.1,
                'reviews_count' => 190,
                'size' => 'S , M'
            ],
            [
                'name' => 'Sky Blue Sunglass',
                'image' => 'p-9.png',
                'price' => 77.00,
                'stock' => 784,
                'sold' => 443,
                'category' => 'Sunglass',
                'rating' => 3.5,
                'reviews_count' => 298,
                'size' => 'S , M'
            ],
            [
                'name' => 'Kid\'s Yellow T-shirt',
                'image' => 'p-10.png',
                'price' => 110.00,
                'stock' => 650,
                'sold' => 365,
                'category' => 'Fashion',
                'rating' => 4.1,
                'reviews_count' => 156,
                'size' => 'S'
            ],
            [
                'name' => 'White Rubber Band Smart Watch',
                'image' => 'p-11.png',
                'price' => 77.00,
                'stock' => 98,
                'sold' => 241,
                'category' => 'Electronics',
                'rating' => 3.4,
                'reviews_count' => 201,
                'size' => 'S , M'
            ],
            [
                'name' => 'Men Brown Leather Shoes',
                'image' => 'p-12.png',
                'price' => 222.00,
                'stock' => 176,
                'sold' => 658,
                'category' => 'Shoes',
                'rating' => 4.1,
                'reviews_count' => 370,
                'size' => '40 , 41 , 42 , 43'
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
