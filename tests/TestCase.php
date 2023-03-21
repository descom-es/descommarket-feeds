<?php

namespace Descom\DescomMarket\Feeds\Tests;

use Descom\DescomMarket\Feeds\DescomMarketFeedsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function getPackageProviders($app)
    {
        return [
            DescomMarketFeedsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
    }
}
