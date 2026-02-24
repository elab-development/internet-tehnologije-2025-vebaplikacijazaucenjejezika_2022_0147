<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Language;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = User::where('role', 'teacher')->pluck('id');

        if (Language::count() === 0) {
            $this->call(LanguageSeeder::class);
        }

        $languages = Language::orderBy('name')->get()->keyBy('name');

        $catalog = [
            'English' => [
                ['title' => 'English A1 – Basics & Survival', 'level' => 'A1', 'is_active' => true],
                ['title' => 'English A2 – Everyday Conversations', 'level' => 'A2', 'is_active' => true],
                ['title' => 'English B1 – Work & Travel', 'level' => 'B1', 'is_active' => true],
            ],
            'German' => [
                ['title' => 'German A1 – Grundlagen', 'level' => 'A1', 'is_active' => true],
                ['title' => 'German A2 – Alltag & Einkaufen', 'level' => 'A2', 'is_active' => true],
                ['title' => 'German B1 – Beruf & Reisen', 'level' => 'B1', 'is_active' => true],
            ],
            'Spanish' => [
                ['title' => 'Spanish A1 – Básico', 'level' => 'A1', 'is_active' => true],
                ['title' => 'Spanish A2 – Conversación diaria', 'level' => 'A2', 'is_active' => true],
                ['title' => 'Spanish B1 – Viajes & Cultura', 'level' => 'B1', 'is_active' => true],
            ],
            'French' => [
                ['title' => 'French A1 – Débutant', 'level' => 'A1', 'is_active' => true],
                ['title' => 'French A2 – Vie quotidienne', 'level' => 'A2', 'is_active' => true],
                ['title' => 'French B1 – Travail & Voyages', 'level' => 'B1', 'is_active' => true],
            ],
            'Italian' => [
                ['title' => 'Italian A1 – Principianti', 'level' => 'A1', 'is_active' => true],
                ['title' => 'Italian A2 – Conversazione', 'level' => 'A2', 'is_active' => true],
                ['title' => 'Italian B1 – Lavoro & Viaggi', 'level' => 'B1', 'is_active' => true],
            ],
        ];


        foreach ($catalog as $languageName => $courses) {
            $language = $languages->get($languageName);
            if (!$language) {
                continue;
            }

            foreach ($courses as $c) {
                Course::updateOrCreate(
                    [
                        'title' => $c['title'],
                        'language_id' => $language->id,
                        'level' => $c['level'],
                    ],
                    [
                        'teacher_id' => $teachers->isNotEmpty() ? $teachers->random() : null,
                        'is_active' => (bool) ($c['is_active'] ?? true),
                    ]
                );
            }
        }
    }
}
