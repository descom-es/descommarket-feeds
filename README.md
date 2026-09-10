# Descom Market Feeds

[![tests](https://github.com/descom-es/descommarket-feeds/actions/workflows/tests.yml/badge.svg)](https://github.com/descom-es/descommarket-feeds/actions/workflows/tests.yml)
[![analyze](https://github.com/descom-es/descommarket-feeds/actions/workflows/analyze.yml/badge.svg)](https://github.com/descom-es/descommarket-feeds/actions/workflows/analyze.yml)
[![style](https://github.com/descom-es/descommarket-feeds/actions/workflows/style_fix.yml/badge.svg)](https://github.com/descom-es/descommarket-feeds/actions/workflows/style_fix.yml)

## Add to .env

```bash
GOOGLE_API_CREDENTIALS_PATH=

GOOGLE_MERCHANT_ID=
GOOGLE_MERCHANT_DATA_SOURCE=
GOOGLE_MERCHANT_DATA_SOURCE_NAME=
GOOGLE_MERCHANT_QUEUE_CONNECTION=sync
GOOGLE_MERCHANT_QUEUE_TRIES=10

#Optional, only if you want to override the default values in the config file
GOOGLE_MERCHANT_FEED_LABEL=ES
GOOGLE_MERCHANT_CONTENT_LANGUAGE=es
GOOGLE_MERCHANT_TRANSPORT=rest

GOOGLE_INDEX_ENABLED=
GOOGLE_INDEX_QUEUE_CONNECTION=sync
GOOGLE_INDEX_QUEUE_TRIES=10
```

## Add config

```bash
php artisan vendor:publish --provider="DescomMarket\Feeds\DescomMarketFeedsServiceProvider"
```

## Google Merchant

Products go to Merchant Center through the **Merchant API**. Google sunset the Content
API for Shopping on August 18, 2026.

Set up an account in this order:

1. Give the service account **admin** access to the merchant account, which needs a
   verified website.
2. Register the GCP project: `dm360:google:merchant:developer-registration --register`.
3. Put the data source id in `GOOGLE_MERCHANT_DATA_SOURCE`.

Then the package works on its own, in the `google_merchant` queue:

```php
use DescomMarket\Common\Events\Catalog\Products\ProductPublished;   // insert
use DescomMarket\Common\Events\Catalog\Products\ProductUpdated;     // insert (overwrites)
use DescomMarket\Common\Events\Catalog\Products\ProductUnpublished; // delete
```

### Developer registration

The Merchant API rejects **every** call, reads included, until the GCP project used to
authenticate is registered with the merchant account: a 401 carrying
`GCP_NOT_REGISTERED`. There is no UI for it.

```bash
php artisan dm360:google:merchant:developer-registration            # check
php artisan dm360:google:merchant:developer-registration --register # register
```

The check exits with a failure code when no project is registered. Google then needs
about five minutes before it accepts calls.

**It only registers once.** A second `registerGcp` returns 409, so nothing can be
corrected that way afterwards: a botched registration has to be restarted.

What every account needs is **at least one user holding the API developer role**.
Removing the last one blocks API calls after a 30 day grace period. The role only
receives the notifications, so it stacks on whatever access the user already has: an
existing admin can take it, and a user who only holds it needs `STANDARD` or `ADMIN` on
top to do anything else. It lives in `Access and services` > `People and access`.

There are two ways to get there:

- **Without a contact.** `registerGcp` only links the GCP project, and you grant the
  role to a user that already exists, in the Merchant Center UI.
- **With a contact**, `--register --email=someone@example.com`. Google invites that
  address and grants it the role, but the registration is only complete once the
  invitation is accepted, within 14 days. If the link expires, API calls stop and the
  registration has to be started over. The address must be a **Google account** and
  cannot be a service account, or the invitation can never be accepted.

`unregisterGcp` on the same resource undoes a registration. It is deliberately not wired
into the command.

### Data source

The Merchant API cannot take a product without a data source. The package resolves one
on the first call of each process:

1. With `GOOGLE_MERCHANT_DATA_SOURCE` set, that id is used and nothing is looked up.
2. Otherwise, the **primary product data source whose display name** matches
   `GOOGLE_MERCHANT_DATA_SOURCE_NAME`, which is then required. It is created when
   missing, and two sources sharing that name fail instead of picking one.

**Pin the id on any account that already sent products with the Content API.** A product
is identified per account by `contentLanguage~feedLabel~offerId`, not per data source, so
sending it to another primary source does not duplicate it: it **moves** it — offer
stealing, in Google's words — and changes the rules and ownership that applied. Content
API sources stay compatible and show up as `Content API` in the UI, but their display
name is whatever Merchant Center gave them, so a name lookup may or may not land on the
right one: when it misses it creates a second source and moves the catalogue into it, one
product per call. Deletes send the data source too, so a wrong pin answers `NOT_FOUND`,
which the package reads as "already gone" while the product stays published.

Ids come from `DataSourceService::list()`, or from `products.get` on a product already
up, which reports the source it belongs to. Google generates them, they cannot be chosen.

`GOOGLE_MERCHANT_FEED_LABEL` and `GOOGLE_MERCHANT_CONTENT_LANGUAGE` are set on the data
source when it is created and sent with every product. **They must match**, or Merchant
Center rejects the products. Changing them later does not update an existing data
source: create a new one instead.

### Credentials

The service account in `GOOGLE_API_CREDENTIALS_PATH` needs the
`https://www.googleapis.com/auth/content` scope and access to the Merchant Center
account in `GOOGLE_MERCHANT_ID`.

`GOOGLE_MERCHANT_TRANSPORT` is `rest` by default. Use `grpc` only where the PHP `grpc`
extension is installed.

### Data sources API

```php
use DescomMarket\Feeds\Google\Merchant\Services\DataSources\DataSourceService;

DataSourceService::list();                 // every data source of the account
DataSourceService::resolve();              // the one products are sent to, created if missing
DataSourceService::create('My feed name'); // a new primary product data source
```

```php
use DescomMarket\Feeds\Google\Merchant\Services\DeveloperRegistration\DeveloperRegistrationService;

DeveloperRegistrationService::gcpIds();               // registered projects, empty if none
DeveloperRegistrationService::registerGcp($email);    // what the command does
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
