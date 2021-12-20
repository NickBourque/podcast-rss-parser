<?php

namespace App\Services;

use SimplePie as RssFeed;
use SimplePie_Item as RssFeedItem;
use Vedmant\FeedReader\Facades\FeedReader;

class PodcastRssFeedParserService
{
    public function __construct(private FeedReader $feedReader) { }


    public function fetch(string $url): RssFeed
    {
        return $this->feedReader::read($url);
    }


    public function getTitle(RssFeed $rssFeed): ?string
    {
        return $rssFeed->get_title();
    }


    public function getDescription(RssFeed $rssFeed): ?string
    {
        return $rssFeed->get_description();
    }


    public function getArtworkUrl(RssFeed $rssFeed): ?string
    {
        return $rssFeed->get_image_url();
    }


    public function getWebsiteUrl(RssFeed $rssFeed): ?string
    {
        return $rssFeed->get_link();
    }


    public function getLanguage(RssFeed $rssFeed): ?string
    {
        return $rssFeed->get_language();
    }


    /**
     * @param RssFeed $rssFeed
     *
     * @return RssFeedItem[]
     */
    public function getItems(RssFeed $rssFeed): array
    {
        return $rssFeed->get_items();
    }


    public function getItemTitle(RssFeedItem $rssFeedItem): ?string
    {
        return $rssFeedItem->get_title();
    }


    public function getItemDescription(RssFeedItem $rssFeedItem): ?string
    {
        return $rssFeedItem->get_description();
    }


    public function getItemAudioUrl(RssFeedItem $rssFeedItem): ?string
    {
        return $rssFeedItem->get_enclosure()?->get_link();
    }


    public function getItemEpisodeUrl(RssFeedItem $rssFeedItem): ?string
    {
        return $rssFeedItem->get_link();
    }
}
