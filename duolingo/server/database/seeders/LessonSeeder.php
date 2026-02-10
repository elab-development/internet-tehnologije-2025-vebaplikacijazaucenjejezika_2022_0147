<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $courses = Course::with('language')
            ->whereNotNull('teacher_id')
            ->get();

        if ($courses->isEmpty()) {
            return;
        }

        foreach ($courses as $course) {
            $languageName = $course->language?->name ?? 'Unknown';
            $level = $course->level;

            $lessonTitles = $this->getLessonPlan($languageName, $level);

            if (empty($lessonTitles)) {
                continue;
            }

            Lesson::where('course_id', $course->id)->delete();

            $baseDate = Carbon::now()->addDays(rand(3, 14));

            foreach ($lessonTitles as $i => $title) {
                $dayOffset = (int) floor($i * rand(2, 4));
                $start = (clone $baseDate)->addDays($dayOffset)->setTime(rand(9, 19), [0, 30][rand(0, 1)]);
                $end   = (clone $start)->addMinutes([60, 90][rand(0, 1)]);

                Lesson::create([
                    'course_id' => $course->id,
                    'teacher_id' => $course->teacher_id,
                    'title' => $title,
                    'starts_at' => $start,
                    'ends_at' => $end,
                ]);
            }
        }
    }

    private function getLessonPlan(string $language, string $level): array
    {
        $commonA1 = [
            'Unit 1: Alphabet & Pronunciation',
            'Unit 2: Greetings & Introductions',
            'Unit 3: Numbers, Time & Dates',
            'Unit 4: Family & People',
            'Unit 5: Food & Drinks',
            'Unit 6: Around Town (Directions)',
            'Unit 7: Everyday Verbs (Present Tense)',
            'Unit 8: Shopping & Prices',
            'Unit 9: Weather & Seasons',
            'Unit 10: Review & Mini Quiz',
        ];

        $commonA2 = [
            'Unit 1: Daily Routine & Habits',
            'Unit 2: Present Continuous / Ongoing Actions',
            'Unit 3: Past Tense (Basics)',
            'Unit 4: Travel: Hotel, Airport, Tickets',
            'Unit 5: Health: Pharmacy & Doctor',
            'Unit 6: At Work: Emails & Meetings',
            'Unit 7: Opinions & Preferences',
            'Unit 8: Future Plans (Going to / Will)',
            'Unit 9: Stories: Short Dialogues',
            'Unit 10: Review & Speaking Practice',
        ];

        $commonB1 = [
            'Unit 1: Past Tense (Storytelling)',
            'Unit 2: Describing People & Experiences',
            'Unit 3: Work & Career (Interviews)',
            'Unit 4: Travel Problems & Solutions',
            'Unit 5: Media & Technology',
            'Unit 6: Culture & Traditions',
            'Unit 7: Expressing Agreement/Disagreement',
            'Unit 8: Conditional (If… then…) Basics',
            'Unit 9: Formal vs Informal Communication',
            'Unit 10: Final Review & Assessment',
        ];

        $specific = match ($language) {
            'German' => [
                'A1' => [
                    'Unit X: Articles (der/die/das)',
                    'Unit Y: Separable Verbs (Basics)',
                ],
                'A2' => [
                    'Unit X: Akkusativ & Dativ (Basics)',
                    'Unit Y: Modal Verbs in Practice',
                ],
                'B1' => [
                    'Unit X: Nebensätze (weil, dass) – Intro',
                    'Unit Y: Passive Voice (Basics)',
                ],
            ],
            'French' => [
                'A1' => [
                    'Unit X: Gender (le/la) & Basic Articles',
                    'Unit Y: Être vs Avoir',
                ],
                'A2' => [
                    'Unit X: Passé Composé (Basics)',
                    'Unit Y: Pronouns (y, en) – Intro',
                ],
                'B1' => [
                    'Unit X: Imparfait vs Passé Composé',
                    'Unit Y: Subjunctive (Intro)',
                ],
            ],
            'Spanish' => [
                'A1' => [
                    'Unit X: Ser vs Estar (Basics)',
                    'Unit Y: Gender & Articles (el/la)',
                ],
                'A2' => [
                    'Unit X: Preterite (Basics)',
                    'Unit Y: Direct/Indirect Object Pronouns',
                ],
                'B1' => [
                    'Unit X: Imperfect vs Preterite',
                    'Unit Y: Subjuntivo (Intro)',
                ],
            ],
            'Italian' => [
                'A1' => [
                    'Unit X: Articles (il/lo/la) – Basics',
                    'Unit Y: Essere vs Avere',
                ],
                'A2' => [
                    'Unit X: Passato Prossimo (Basics)',
                    'Unit Y: Pronomi (lo/la/li/le) – Intro',
                ],
                'B1' => [
                    'Unit X: Imperfetto vs Passato Prossimo',
                    'Unit Y: Congiuntivo (Intro)',
                ],
            ],
            default => [
                'A1' => [],
                'A2' => [],
                'B1' => [],
            ],
        };

        $base = match ($level) {
            'A1' => $commonA1,
            'A2' => $commonA2,
            'B1' => $commonB1,
            default => [],
        };

        $extras = $specific[$level] ?? [];
        if (!empty($base) && !empty($extras)) {
            array_splice($base, 4, 0, [$extras[0]]);
            array_splice($base, 7, 0, [$extras[1]]);
        }

        return array_map(fn($t) => "{$language} {$level}: {$t}", $base);
    }
}
