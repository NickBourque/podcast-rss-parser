<?php

namespace Tests\Feature;

use App\Models\Episode;
use App\Models\Podcast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Mockery;
use SimplePie;
use SimplePie_Item;
use Tests\TestCase;
use Vedmant\FeedReader\Facades\FeedReader;

class ImportRssFeedCommandTest extends TestCase
{
    use RefreshDatabase;


    public function testRunCommand()
    {
        $this->assertEquals(0, Podcast::count());
        $this->assertEquals(0, Episode::count());

        $enclosureMock1 = Mockery::mock(\SimplePie_Enclosure::class);
        $enclosureMock1->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some audio url 1');

        $enclosureMock2 = Mockery::mock(\SimplePie_Enclosure::class);
        $enclosureMock2->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some audio url 2');

        $rssItemMock1 = Mockery::mock(SimplePie_Item::class);
        $rssItemMock1->shouldReceive('get_title')->once()->withAnyArgs()->andReturn('Some Item Title');
        $rssItemMock1->shouldReceive('get_description')->once()->withAnyArgs()->andReturn('Some Item Description');
        $rssItemMock1->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some Item Url');
        $rssItemMock1->shouldReceive('get_enclosure')->once()->withAnyArgs()->andReturn($enclosureMock1);

        $rssItemMock2 = Mockery::mock(SimplePie_Item::class);
        $rssItemMock2->shouldReceive('get_title')->once()->withAnyArgs()->andReturn('Some Item Title');
        $rssItemMock2->shouldReceive('get_description')->once()->withAnyArgs()->andReturn('Some Item Description');
        $rssItemMock2->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some Item Url');
        $rssItemMock2->shouldReceive('get_enclosure')->once()->withAnyArgs()->andReturn($enclosureMock2);

        $rssFeedMock = Mockery::mock(SimplePie::class);
        $rssFeedMock->shouldReceive('get_title')->once()->withAnyArgs()->andReturn('Some Title');
        $rssFeedMock->shouldReceive('get_description')->once()->withAnyArgs()->andReturn('Some Description');
        $rssFeedMock->shouldReceive('get_image_url')->once()->withAnyArgs()->andReturn('Some Url');
        $rssFeedMock->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some Url');
        $rssFeedMock->shouldReceive('get_language')->once()->withAnyArgs()->andReturn('Some Language');
        $rssFeedMock->shouldReceive('get_items')->once()->withAnyArgs()->andReturn([
            $rssItemMock1,
            $rssItemMock2
        ]);
        app()->instance(SimplePie::class, $rssFeedMock);

        FeedReader::shouldReceive('read')->once()->with('https://someUrl.com')->andReturn($rssFeedMock);

        Artisan::call('rss:import https://someUrl.com');

        $this->assertEquals(1, Podcast::count());
        $this->assertEquals(2, Episode::count());

        $podcast = Podcast::first();

        $this->assertCount(2, $podcast->episodes);

        //unset timestamps for comparison because we don't know them
        $podcastArray = $podcast->toArray();
        unset($podcastArray['created_at']);
        unset($podcastArray['updated_at']);
        unset($podcastArray['episodes'][0]['created_at']);
        unset($podcastArray['episodes'][0]['updated_at']);
        unset($podcastArray['episodes'][1]['created_at']);
        unset($podcastArray['episodes'][1]['updated_at']);

        $this->assertEquals([
            'id' => 1,
            'title' => 'Some Title',
            'artwork_url' => 'Some Url',
            'rss_feed_url' => 'https://someUrl.com',
            'description' => 'Some Description',
            'language' => 'Some Language',
            'website_url' => 'Some Url',
            'episodes' => [
                [
                    'id' => 1,
                    'podcast_id' => 1,
                    'title' => 'Some Item Title',
                    'description' => 'Some Item Description',
                    'audio_url' => 'Some audio url 1',
                    'episode_url' => 'Some Item Url',
                ],
                [
                    'id' => 2,
                    'podcast_id' => 1,
                    'title' => 'Some Item Title',
                    'description' => 'Some Item Description',
                    'audio_url' => 'Some audio url 2',
                    'episode_url' => 'Some Item Url',
                ],
            ]
        ], $podcastArray);
    }


    public function testRunCommandRollsBackIfExceptionIsThrown()
    {
        $this->assertEquals(0, Podcast::count());
        $this->assertEquals(0, Episode::count());

        $enclosureMock1 = Mockery::mock(\SimplePie_Enclosure::class);
        $enclosureMock1->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some audio url 1');

        $rssItemMock1 = Mockery::mock(SimplePie_Item::class);
        $rssItemMock1->shouldReceive('get_title')->once()->withAnyArgs()->andReturn('Some Item Title');
        $rssItemMock1->shouldReceive('get_description')->once()->withAnyArgs()->andReturn('Some Item Description');
        $rssItemMock1->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some Item Url');
        $rssItemMock1->shouldReceive('get_enclosure')->once()->withAnyArgs()->andReturn($enclosureMock1);

        $rssItemMock2 = Mockery::mock(SimplePie_Item::class);
        $rssItemMock2->shouldReceive('get_title')->once()->withAnyArgs()->andThrow(new \Exception('Some generic exception was thrown.'));

        $rssFeedMock = Mockery::mock(SimplePie::class);
        $rssFeedMock->shouldReceive('get_title')->once()->withAnyArgs()->andReturn('Some Title');
        $rssFeedMock->shouldReceive('get_description')->once()->withAnyArgs()->andReturn('Some Description');
        $rssFeedMock->shouldReceive('get_image_url')->once()->withAnyArgs()->andReturn('Some Url');
        $rssFeedMock->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some Url');
        $rssFeedMock->shouldReceive('get_language')->once()->withAnyArgs()->andReturn('Some Language');
        $rssFeedMock->shouldReceive('get_items')->once()->withAnyArgs()->andReturn([
            $rssItemMock1,
            $rssItemMock2
        ]);
        app()->instance(SimplePie::class, $rssFeedMock);

        FeedReader::shouldReceive('read')->once()->with('https://someUrl.com')->andReturn($rssFeedMock);

        try {
            Artisan::call('rss:import https://someUrl.com');
        } catch (\Exception $e) {
            //instead of expecting the exception, we'll catch it, so we can continue the test
            //and ensure the rollback works and no entities get created
            $this->assertEquals('Some generic exception was thrown.', $e->getMessage());
        }

        $this->assertEquals(0, Podcast::count());
        $this->assertEquals(0, Episode::count());
    }
}
