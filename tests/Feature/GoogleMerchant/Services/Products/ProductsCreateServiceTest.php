<?php

namespace DescomMarket\Feeds\Tests\Feature\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\Google\GoogleApiBuilder;
use DescomMarket\Feeds\Google\GoogleServiceBuilder;
use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductDeleteService;
use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductInsertService;
use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductsDeleteService;
use DescomMarket\Feeds\Google\Merchant\Services\Products\ProductsInsertService;
use DescomMarket\Feeds\Tests\TestCase;
use Google\Client;
use Google\Service\ShoppingContent;
use Google\Service\ShoppingContent\Product;
use Google\Service\ShoppingContent\Resource\Products;

class ProductsCreateServiceTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function testA()
    {
    }
}
