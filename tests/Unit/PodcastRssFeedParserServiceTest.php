<?php

namespace Tests\Unit;

use App\Services\PodcastRssFeedParserService;
use Mockery;
use PHPUnit\Framework\TestCase;
use SimplePie;
use SimplePie_Enclosure;
use SimplePie_Item;
use Vedmant\FeedReader\Facades\FeedReader;

class PodcastRssFeedParserServiceTest extends TestCase
{
    public function testFetch()
    {
        $rssFeed = new SimplePie();
        FeedReader::shouldReceive('read')->once()->with('https://someUrl.com')->andReturn($rssFeed);
        $this->assertEquals($rssFeed, app(PodcastRssFeedParserService::class)->fetch('https://someUrl.com'));
    }


    public function testGetTitle()
    {
        $rssFeedMock = Mockery::mock(SimplePie::class);
        $rssFeedMock->shouldReceive('get_title')->once()->withAnyArgs()->andReturn('Some Title');
        app()->instance(SimplePie::class, $rssFeedMock);

        $this->assertEquals('Some Title', app(PodcastRssFeedParserService::class)->getTitle($rssFeedMock));
    }


    public function testGetDescription()
    {
        $rssFeedMock = Mockery::mock(SimplePie::class);
        $rssFeedMock->shouldReceive('get_description')->once()->withAnyArgs()->andReturn('Some Description');
        app()->instance(SimplePie::class, $rssFeedMock);

        $this->assertEquals('Some Description', app(PodcastRssFeedParserService::class)->getDescription($rssFeedMock));
    }


    public function testGetArtworkUrl()
    {
        $rssFeedMock = Mockery::mock(SimplePie::class);
        $rssFeedMock->shouldReceive('get_image_url')->once()->withAnyArgs()->andReturn('Some Url');
        app()->instance(SimplePie::class, $rssFeedMock);

        $this->assertEquals('Some Url', app(PodcastRssFeedParserService::class)->getArtworkUrl($rssFeedMock));
    }


    public function testGetWebsiteUrl()
    {
        $rssFeedMock = Mockery::mock(SimplePie::class);
        $rssFeedMock->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some Url');
        app()->instance(SimplePie::class, $rssFeedMock);

        $this->assertEquals('Some Url', app(PodcastRssFeedParserService::class)->getWebsiteUrl($rssFeedMock));
    }


    public function testGetLanguage()
    {
        $rssFeedMock = Mockery::mock(SimplePie::class);
        $rssFeedMock->shouldReceive('get_language')->once()->withAnyArgs()->andReturn('Some Lang Code');
        app()->instance(SimplePie::class, $rssFeedMock);

        $this->assertEquals('Some Lang Code', app(PodcastRssFeedParserService::class)->getLanguage($rssFeedMock));
    }


    public function testGetItems()
    {
        $rssFeedItemMock1 = Mockery::mock(SimplePie_Item::class);
        $rssFeedItemMock2 = Mockery::mock(SimplePie_Item::class);

        $rssFeedMock = Mockery::mock(SimplePie::class);
        $rssFeedMock->shouldReceive('get_items')->once()->withAnyArgs()->andReturn([
            $rssFeedItemMock1,
            $rssFeedItemMock2
        ]);
        app()->instance(SimplePie::class, $rssFeedMock);

        $this->assertEquals([
            $rssFeedItemMock1,
            $rssFeedItemMock2
        ], app(PodcastRssFeedParserService::class)->getItems($rssFeedMock));
    }


    public function testGetItemTitle()
    {
        $rssFeedItemMock = Mockery::mock(SimplePie_Item::class);
        $rssFeedItemMock->shouldReceive('get_title')->once()->withAnyArgs()->andReturn('Some Title');
        app()->instance(SimplePie_Item::class, $rssFeedItemMock);

        $this->assertEquals('Some Title', app(PodcastRssFeedParserService::class)->getItemTitle($rssFeedItemMock));
    }


    public function testGetItemDescription()
    {
        $rssFeedItemMock = Mockery::mock(SimplePie_Item::class);
        $rssFeedItemMock->shouldReceive('get_description')->once()->withAnyArgs()->andReturn('Some Description');
        app()->instance(SimplePie_Item::class, $rssFeedItemMock);

        $this->assertEquals('Some Description', app(PodcastRssFeedParserService::class)->getItemDescription($rssFeedItemMock));
    }


    public function testGetItemAudioUrl()
    {
        $enclosureMock = Mockery::mock(SimplePie_Enclosure::class);
        $enclosureMock->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some audio url');

        $rssFeedItemMock = Mockery::mock(SimplePie_Item::class);
        $rssFeedItemMock->shouldReceive('get_enclosure')->once()->withAnyArgs()->andReturn($enclosureMock);
        app()->instance(SimplePie_Item::class, $rssFeedItemMock);

        $this->assertEquals('Some audio url', app(PodcastRssFeedParserService::class)->getItemAudioUrl($rssFeedItemMock));
    }


    public function testGetItemEpisodeUrl()
    {
        $rssFeedItemMock = Mockery::mock(SimplePie_Item::class);
        $rssFeedItemMock->shouldReceive('get_link')->once()->withAnyArgs()->andReturn('Some Episode Url');
        app()->instance(SimplePie_Item::class, $rssFeedItemMock);

        $this->assertEquals('Some Episode Url', app(PodcastRssFeedParserService::class)->getItemEpisodeUrl($rssFeedItemMock));
    }
}
