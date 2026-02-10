<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title'  => $this->title,
            'language_id' => $this->language_id,
            'language' => $this->whenLoaded('language', fn() => new LanguageResource($this->language)),
            'level'  => $this->level,
            'teacher_id' => $this->teacher_id,
            'is_active' => (bool) $this->is_active,
        ];
    }
}
