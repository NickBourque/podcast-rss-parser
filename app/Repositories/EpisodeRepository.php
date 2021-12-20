<?php

namespace App\Repositories;

use App\Models\Episode;
use Illuminate\Database\Eloquent\Model;

/**
 * EpisodeRepository
 *
 * @method Episode|Model create(array $attributes)
 *
 */
class EpisodeRepository extends BaseRepository
{
    protected function getModelClassName(): string
    {
        return Episode::class;
    }
}
