<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paintNames = ['Cat Tembok Nippon Paint', 'Cat Dulux Pentalite', 'Cat Aquaproof', 'Cat Avian Kayu & Besi', 'Kuas Eterna 4 Inch', 'Roller Cat Tembok', 'Thinner Impala', 'Dempul Isamu', 'Cat semprot Pilox', 'Cat Propan Woodstain', 'Cat Vinilex Super', 'Cat Paragon Putih', 'Cat Jotun Majestic', 'Cat Kuda Terbang', 'Amplas Lembaran'];
        $name = fake()->randomElement($paintNames).' '.fake()->word();

        // Gambar produk lokal di public/images/products — tidak butuh internet.
        $hardwareImages = [
            'products/cat-interior.jpg',
            'products/cat-eksterior.jpg',
            'products/cat-kayu-besi.jpg',
            'products/alat-pengecatan.jpg',
            'products/thinner-pelarut.jpg',
            'products/cat-tembok-putih.jpg',
            'products/painting.jpg',
        ];

        return [
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'name' => ucwords($name),
            'image' => fake()->randomElement($hardwareImages),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(25, 300) * 1000,
            'stock' => fake()->numberBetween(10, 100),
            'bad_stock' => fake()->numberBetween(0, 5),
            'status' => fake()->randomElement(['active', 'inactive']),
        ];
    }
}
