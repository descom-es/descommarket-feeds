<?php

namespace DescomMarket\Feeds\Google;

use DescomMarket\Common\Events\Catalog\Products\ProductPublished;
use DescomMarket\Common\Repositories\Catalog\Products\ProductRepository;
use DescomMarket\Feeds\Google\Index\Services\EnqueueUrlService;
use DescomMarket\Feeds\Tests\Stubs\ProductRepositoryDriver;
use DescomMarket\Feeds\Tests\TestCase;
use Google\Service\AdExchangeBuyerII\Product;
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

        EnqueueUrlService::publish($url);

        $this->assertDatabaseHas('google_url_indexing_queue', [
            'url' => $url,
            'action' => 'index'
        ]);
    }

    public function test_index_url_twice()
    {
        $url = 'https://www.pipo.es';

        EnqueueUrlService::publish($url);
        EnqueueUrlService::publish($url);

        $this->assertDatabaseCount('google_url_indexing_queue', 1);
    }

    public function test_index_url_delete()
    {
        $url = 'https://www.descom.es';

        EnqueueUrlService::unpublish($url);
        EnqueueUrlService::publish($url);

        $this->assertDatabaseCount('google_url_indexing_queue', 0);
    }

    public function test_index_and_unindex_url()
    {
        $url = 'https://www.descom.es';

        EnqueueUrlService::publish($url);
        EnqueueUrlService::unpublish($url);

        $this->assertDatabaseCount('google_url_indexing_queue', 0);
    }

    public function test_unindex_url()
    {
        $url = 'https://www.descom.es';

        EnqueueUrlService::unpublish($url);

        $this->assertDatabaseHas('google_url_indexing_queue', [
            'url' => $url,
            'action' => 'unindex'
        ]);
    }

    public function test_product_index_if_published()
    {
        ProductRepository::config(new ProductRepositoryDriver());

        event(new ProductPublished(1));

        $this->assertDatabaseHas('google_url_indexing_queue', [
            'url' => 'https://example.com',
            'action' => 'index'
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
