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

        $hardwareImages = [
            'https://images.unsplash.com/photo-1572981779307-38b8cabb2407?auto=format&fit=crop&q=80&w=800', // Drill
            'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?auto=format&fit=crop&q=80&w=800', // Hardware tools
            'https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&q=80&w=800', // Measuring tape
            'https://images.unsplash.com/photo-1530124566582-a618bc2615dc?auto=format&fit=crop&q=80&w=800', // Paint brushes
            'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&q=80&w=800', // Interior paint
            'https://images.unsplash.com/photo-1581092580497-e0d23cbdf1dc?auto=format&fit=crop&q=80&w=800', // Industrial tools
            'https://images.unsplash.com/photo-1540103711724-ebf833bde8d0?auto=format&fit=crop&q=80&w=800', // Woodworking
            'https://images.unsplash.com/photo-1562259929-b4e1fd3aef09?auto=format&fit=crop&q=80&w=800', // Screwdriver
            'https://images.unsplash.com/photo-1525909002-1b05e0c869d8?auto=format&fit=crop&q=80&w=800', // Paint rollers
            'https://images.unsplash.com/photo-1580130281320-0ef0754f2bf7?auto=format&fit=crop&q=80&w=800',  // Wrench set
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
