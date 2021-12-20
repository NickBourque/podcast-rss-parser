<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Episode
 *
 * @property int          $id
 * @property int          $podcast_id
 * @property string       $title
 * @property string       $description
 * @property string       $audio_url
 * @property string|null  $episode_url
 * @property-read Podcast $podcast
 */
class Episode extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at'];


    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }
}
