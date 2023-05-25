<?php

namespace DescomMarket\Feeds\Google;

use DescomMarket\Feeds\Google\Index\Services\EnqueueUrlService;
use DescomMarket\Feeds\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EnqueueUrlToIndexerTest extends TestCase
{

    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        config(['feeds-google.index.enabled' => true]);
    }

    public function test_index_url()
    {
        $url = 'https://www.pipo.es';

        EnqueueUrlService::index($url);

        $this->assertDatabaseHas('google_url_indexing_queue', [
            'url' => $url,
            'action' => 'index'
        ]);
    }

    public function test_index_url_twice()
    {
        $url = 'https://www.pipo.es';

        EnqueueUrlService::index($url);
        EnqueueUrlService::index($url);

        $this->assertDatabaseCount('google_url_indexing_queue', 1);
    }

    public function test_index_url_delete()
    {
        $url = 'https://www.descom.es';

        EnqueueUrlService::unindex($url);
        EnqueueUrlService::index($url);

        $this->assertDatabaseCount('google_url_indexing_queue', 0);
    }

    public function test_index_and_unindex_url()
    {
        $url = 'https://www.descom.es';

        EnqueueUrlService::index($url);
        EnqueueUrlService::unindex($url);

        $this->assertDatabaseCount('google_url_indexing_queue', 0);
    }

    public function test_unindex_url()
    {
        $url = 'https://www.descom.es';

        EnqueueUrlService::unindex($url);

        $this->assertDatabaseHas('google_url_indexing_queue', [
            'url' => $url,
            'action' => 'unindex'
        ]);
    }

    // public function test_index_url_store_command()
    // {
    //     $url = 'https://www.descom.es';

    //     $storeUrl = new UrlStore();
    //     $storeUrl->index($url);

    //     $this->artisan('feeds-google:index-urls');

    //     $this->assertDatabaseCount('google_url_indexing_queue', 0);
    // }
}
