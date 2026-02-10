<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        $languages = [
            [
                'name' => 'English',
                'img_url' => 'https://flaglog.com/img/england1277.png'
            ],
            [
                'name' => 'German',
                'img_url' => 'https://flaglog.com/img/germany1949.png'
            ],
            [
                'name' => 'Spanish',
                'img_url' => 'https://flaglog.com/img/spain1981.png'
            ],
            [
                'name' => 'French',
                'img_url' => 'https://flaglog.com/img/france1794.png'
            ],
            [
                'name' => 'Italian',
                'img_url' => 'https://flaglog.com/img/italy1946.png'
            ],
        ];

        foreach ($languages as $l) {
            Language::updateOrCreate(
                ['name' => $l['name']],
                ['img_url' => $l['img_url']]
            );
        }
    }
}
