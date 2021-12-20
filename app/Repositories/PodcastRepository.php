<?php

namespace App\Repositories;

use App\Models\Podcast;
use Illuminate\Database\Eloquent\Model;

/**
 * PodcastRepository
 *
 * @method Podcast|Model create(array $attributes)
 *
 */
class PodcastRepository extends BaseRepository
{
    protected function getModelClassName(): string
    {
        return Podcast::class;
    }
}
