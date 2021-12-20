<?php

namespace Tests\Feature;

use App\Models\Episode;
use App\Models\Podcast;
use App\Repositories\EpisodeRepository;
use App\Repositories\PodcastRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EpisodeRepositoryTest extends TestCase
{
    use RefreshDatabase;


    public function testCreate()
    {
        $podcast = Podcast::create();

        $this->assertEquals(0, Episode::count());

        $episode1 = app(EpisodeRepository::class)->create([
            'podcast_id'  => $podcast->id,
            'title'       => null,
            'description' => null,
            'audio_url'   => null,
            'episode_url' => null,
        ]);

        $this->assertEquals(1, Episode::count());
        $this->assertEquals($episode1->toArray(), Episode::first()->toArray());

        $this->assertCount(1, $podcast->episodes);

        $episode2 = app(EpisodeRepository::class)->create([
            'podcast_id'  => $podcast->id,
            'title'       => 'Some title',
            'description' => 'Some description',
            'audio_url'   => 'Some audio_url',
            'episode_url' => 'Some episode_url',
        ]);

        $this->assertEquals(2, Episode::count());
        $this->assertEquals($episode2->toArray(), Episode::skip(1)->first()->toArray());

        $this->assertCount(2, $podcast->refresh()->episodes);
    }
}
