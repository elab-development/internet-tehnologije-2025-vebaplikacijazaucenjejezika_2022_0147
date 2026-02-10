<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $language = Language::query()->inRandomOrder()->first() ?? Language::factory()->create();
        $level  = fake()->randomElement(['A1', 'A2', 'B1', 'B2', 'C1', 'C2']);
        $suffix  = fake()->randomElement(['General', 'Conversation', 'Grammar', 'Intensive', 'Workshop']);

        return [
            'title'      => "{$language->name} {$level} – {$suffix}",
            'language_id' => $language->id,
            'level'      => $level,
            'teacher_id' => null,
            'is_active'  => true,
        ];
    }

    public function withTeacher(?User $teacher = null): static
    {
        return $this->state(function () use ($teacher) {
            return [
                'teacher_id' => ($teacher?->id) ?? User::factory()->teacher(),
            ];
        });
    }
}
