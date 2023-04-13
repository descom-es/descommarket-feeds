## Add to .env

```bash
GOOGLE_API_CREDENTIALS_PATH=

GOOGLE_MERCHANT_ID=
GOOGLE_MERCHANT_QUEUE_CONNECTION=sync
GOOGLE_MERCHANT_QUEUE_TRIES=10
```

## Add config

```bash
php artisan vendor:publish --provider="DescomMarket\Feeds\DescomMarketFeedsServiceProvider"
```
