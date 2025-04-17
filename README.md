# Descom Market Feeds

[![tests](https://github.com/descom-es/descommarket-feeds/actions/workflows/tests.yml/badge.svg)](https://github.com/descom-es/descommarket-feeds/actions/workflows/tests.yml)
[![analyze](https://github.com/descom-es/descommarket-feeds/actions/workflows/analyze.yml/badge.svg)](https://github.com/descom-es/descommarket-feeds/actions/workflows/analyze.yml)
[![style](https://github.com/descom-es/descommarket-feeds/actions/workflows/style_fix.yml/badge.svg)](https://github.com/descom-es/descommarket-feeds/actions/workflows/style_fix.yml)

## Add to .env

```bash
GOOGLE_API_CREDENTIALS_PATH=

GOOGLE_MERCHANT_ID=
GOOGLE_MERCHANT_QUEUE_CONNECTION=sync
GOOGLE_MERCHANT_QUEUE_TRIES=10

GOOGLE_INDEX_ENABLED=
GOOGLE_INDEX_QUEUE_CONNECTION=sync
GOOGLE_INDEX_QUEUE_TRIES=10

META_CATALOG_ENABLED=
```

## Add config

```bash
php artisan vendor:publish --provider="DescomMarket\Feeds\DescomMarketFeedsServiceProvider"
```

## Google Indexer Url in Search Console

Automatically index your products in Google Search Console if dispatch event:

`DescomMarket\Common\Events\Catalog\Products\ProductPublished`

You can use this API to index your products in Google Search Console

```php
use DescomMarket\Feeds\Google\Index\Services\EnqueueUrlService;

EnqueueUrlService::publish($url);
EnqueueUrlService::unpublish($url);
```

Or use events:

```php
use DescomMarket\Common\Events\Urls\UrlCreated;
use DescomMarket\Common\Events\Urls\UrlDeleted;
```
