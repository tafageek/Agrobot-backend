<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Tomate fraîche',
                'price' => 1000,
                'unit' => 'kg',
                'description' => 'Tomate fraîche et naturelle, cultivée sans produits chimiques. Idéale pour vos sauces et salades.',
                'image_url' => 'https://images.unsplash.com/photo-1595855759920-86582396756a?w=500',
                'status' => 'En stock'
            ],
            [
                'name' => 'Oignon rouge',
                'price' => 800,
                'unit' => 'kg',
                'description' => 'Oignons rouges frais et croquants.',
                'image_url' => 'https://images.unsplash.com/photo-1618512496248-a07fe8376604?w=500',
                'status' => 'En stock'
            ],
            [
                'name' => 'Carotte',
                'price' => 700,
                'unit' => 'kg',
                'description' => 'Carottes riches en vitamines.',
                'image_url' => 'https://images.unsplash.com/photo-1598170845058-32b996a6957b?w=500',
                'status' => 'En stock'
            ],
            [
                'name' => 'Chou vert',
                'price' => 600,
                'unit' => 'pièce',
                'description' => 'Chou vert bio bien pommé.',
                'image_url' => 'https://images.unsplash.com/photo-1581074817932-af423ba4566e?w=500',
                'status' => 'En stock'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
            $this->call(ProductSeeder::class);
        }
    }
}