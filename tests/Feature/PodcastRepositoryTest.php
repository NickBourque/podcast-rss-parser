<?php

namespace Tests\Feature;

use App\Models\Podcast;
use App\Repositories\PodcastRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PodcastRepositoryTest extends TestCase
{
    use RefreshDatabase;


    public function testCreate()
    {
        $this->assertEquals(0, Podcast::count());

        $podcast1 = app(PodcastRepository::class)->create([
            'title'        => null,
            'artwork_url'  => null,
            'rss_feed_url' => null,
            'description'  => null,
            'language'     => null,
            'website_url'  => null,
        ]);

        $this->assertEquals(1, Podcast::count());
        $this->assertEquals($podcast1->toArray(), Podcast::first()->toArray());

        $podcast2 = app(PodcastRepository::class)->create([
            'title'        => 'Some title',
            'artwork_url'  => 'Some artwork_url',
            'rss_feed_url' => 'Some rss_feed_url',
            'description'  => 'Some description',
            'language'     => 'Some language',
            'website_url'  => 'Some website_url',
        ]);

        $this->assertEquals(2, Podcast::count());
        $this->assertEquals($podcast2->toArray(), Podcast::skip(1)->first()->toArray());
    }
}
