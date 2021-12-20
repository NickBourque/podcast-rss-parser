<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportRssFeed extends Command
{
    protected $signature   = 'rss:import {rssFeedUrl}';
    protected $description = 'Import a podcast RSS feed into the database.';


    public function handle(): ?int
    {
        return 0;
    }
}
