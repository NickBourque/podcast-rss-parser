<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Podcast
 *
 * @property int                       $id
 * @property string                    $title
 * @property string                    $description
 * @property string                    $artwork_url
 * @property string                    $rss_feed_url
 * @property string                    $language
 * @property string                    $website_url
 * @property Carbon                    $created_at
 * @property Carbon                    $updated_at
 * @property-read Collection|Episode[] $episodes
 */
class Podcast extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function episodes(): HasMany
    {
        return $this->hasMany(Episode::class);
    }
}
