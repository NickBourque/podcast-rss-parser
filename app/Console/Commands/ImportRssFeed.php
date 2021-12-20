<?php

namespace App\Console\Commands;

use App\Repositories\EpisodeRepository;
use App\Repositories\PodcastRepository;
use App\Services\PodcastRssFeedParserService;
use Illuminate\Console\Command;

class ImportRssFeed extends Command
{
    protected $signature = 'rss:import {rssFeedUrl}';
    protected $description = 'Import a podcast RSS feed into the database.';


    public function __construct(
        private PodcastRssFeedParserService $podcastRssFeedParserService,
        private PodcastRepository $podcastRepository,
        private EpisodeRepository $episodeRepository,
    ) {
        parent::__construct();
    }


    public function handle(): ?int
    {
        $rssFeedUrl = $this->argument('rssFeedUrl');

        $rssFeed = $this->podcastRssFeedParserService->fetch($rssFeedUrl);

        $podcastAttributes = [
            'title'        => $this->podcastRssFeedParserService->getTitle($rssFeed),
            'description'  => $this->podcastRssFeedParserService->getDescription($rssFeed),
            'artwork_url'  => $this->podcastRssFeedParserService->getArtworkUrl($rssFeed),
            'rss_feed_url' => $rssFeedUrl,
            'website_url'  => $this->podcastRssFeedParserService->getWebsiteUrl($rssFeed),
            'language'     => $this->podcastRssFeedParserService->getLanguage($rssFeed),
        ];

        $podcast = $this->podcastRepository->create($podcastAttributes);

        foreach ($this->podcastRssFeedParserService->getItems($rssFeed) as $rssFeedItem) {
            $episodeAttributes = [
                'podcast_id'  => $podcast->id,
                'title'       => $this->podcastRssFeedParserService->getItemTitle($rssFeedItem),
                'description' => $this->podcastRssFeedParserService->getItemDescription($rssFeedItem),
                'audio_url'   => $this->podcastRssFeedParserService->getItemAudioUrl($rssFeedItem),
                'episode_url' => $this->podcastRssFeedParserService->getItemEpisodeUrl($rssFeedItem),
            ];

            $this->episodeRepository->create($episodeAttributes);
        }

        return 0;
    }
}
