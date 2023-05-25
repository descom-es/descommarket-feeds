<?php

namespace DescomMarket\Feeds\Google;

use DescomMarket\Common\Events\Catalog\Products\ProductPublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\Google\Index\Services\EnqueueUrlService;
use DescomMarket\Feeds\Tests\Stubs\ProductRepositoryDriver;
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

    public function testIndexUrl()
    {
        $url = 'https://www.pipo.es';

        EnqueueUrlService::publish($url);

        $this->assertDatabaseHas('google_url_indexing_queue', [
            'url' => $url,
            'action' => 'index',
        ]);
    }

    public function testIndexUrlTwice()
    {
        $url = 'https://www.pipo.es';

        EnqueueUrlService::publish($url);
        EnqueueUrlService::publish($url);

        $this->assertDatabaseCount('google_url_indexing_queue', 1);
    }

    public function testIndexUrlDelete()
    {
        $url = 'https://www.descom.es';

        EnqueueUrlService::unpublish($url);
        EnqueueUrlService::publish($url);

        $this->assertDatabaseCount('google_url_indexing_queue', 0);
    }

    public function testIndexAndUnindexUrl()
    {
        $url = 'https://www.descom.es';

        EnqueueUrlService::publish($url);
        EnqueueUrlService::unpublish($url);

        $this->assertDatabaseCount('google_url_indexing_queue', 0);
    }

    public function testUnindexUrl()
    {
        $url = 'https://www.descom.es';

        EnqueueUrlService::unpublish($url);

        $this->assertDatabaseHas('google_url_indexing_queue', [
            'url' => $url,
            'action' => 'unindex',
        ]);
    }

    public function testProductIndexIfPublished()
    {
        ProductRepository::config(new ProductRepositoryDriver());

        event(new ProductPublished(1));

        $this->assertDatabaseHas('google_url_indexing_queue', [
            'url' => 'https://example.com',
            'action' => 'index',
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
