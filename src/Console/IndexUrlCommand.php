<?php

namespace DescomMarket\Feeds\Console;

use DescomMarket\Feeds\Google\Index\Services\IndexUrlService;
use DescomMarket\Feeds\Google\Index\Services\UnIndexUrlService;
use DescomMarket\Feeds\Google\Models\UrlIndexingQueueModel;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Schedule;

class IndexUrlCommand extends Command
{
    protected $signature = 'dm360:google:index';

    protected $description = 'Index urls until exception urls';

    public function handle()
    {

        $schedule = new Schedule();


        $urls = UrlIndexingQueueModel::orderBy('priority', 'asc')->get();

        foreach ($urls as $url) {
            try {
                $action = $url->action === 'index' ? new IndexUrlService() : new UnIndexUrlService();
                $action->run($url->url);

                $url->delete();

                $this->info("{$url->url} indexed");
                logger()->debug("[Google Search] url: {$url->url} indexed");
            } catch (Exception $exception) {
                $this->error("{$url}: " . $exception->getMessage());
                logger()->debug("[Google Search Error] url: {$url->url} failed to indexed", [
                    'message' => $exception->getMessage(),
                ]);

                break;
            }
        }
    }
}
