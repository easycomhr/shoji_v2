<?php

namespace App\Repositories;

use App\Models\LanguageTranslation;

class LanguageTranslationRepository extends BaseRepository
{
    protected function model(): string
    {
        return LanguageTranslation::class;
    }
}
