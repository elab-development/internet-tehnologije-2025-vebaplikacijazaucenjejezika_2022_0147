<?php

namespace Database\Factories;

use App\Models\Language;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
{
    protected $model = Language::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['English', 'German', 'Spanish', 'French', 'Italian', 'Serbian']),
            'img_url' => fake()->optional()->imageUrl(256, 256, 'flag', true),
        ];
    }
}
