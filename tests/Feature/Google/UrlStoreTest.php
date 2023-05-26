<?php

namespace DescomMarket\Feeds\Google;


use DescomMarket\Feeds\Tests\TestCase;
use Google\Models\Indexer;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UrlStoreTest extends TestCase
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

        $storeUrl = new UrlStore();
        $storeUrl->index($url);

        $this->assertDatabaseHas('google_indexing', [
            'url' => $url,
            'action' => 'index'
        ]);
    }

    public function test_index_url_twice()
    {
        $url = 'https://www.pipo.es';

        $storeUrl = new UrlStore();
        $storeUrl->index($url);
        $storeUrl->index($url);

        $this->assertDatabaseCount('google_indexing', 1);
    }

    public function test_index_url_delete()
    {
        $url = 'https://www.descom.es';

        $storeUrl = new UrlStore();
        $storeUrl->unindex($url);
        $storeUrl->index($url);

        $this->assertDatabaseCount('google_indexing', 0);
    }

    public function test_index_and_unindex_url()
    {
        $url = 'https://www.descom.es';

        $storeUrl = new UrlStore();
        $storeUrl->index($url);
        $storeUrl->unindex($url);

        $this->assertDatabaseCount('google_indexing', 0);
    }

    public function test_unindex_url()
    {
        $url = 'https://www.descom.es';

        $storeUrl = new UrlStore();
        $storeUrl->unindex($url);

        $this->assertDatabaseHas('google_indexing', [
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

    //     $this->assertDatabaseCount('google_indexing', 0);
    // }
}
