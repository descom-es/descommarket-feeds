<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products;

use Carbon\Carbon;
use DescomMarket\Feeds\Google\GoogleServiceBuilder;
use DescomMarket\Common\Events\Catalog\Products\ProductPublished;

class ProductsExpirationRenewService
{
    private string $merchantId;
    private int $maxResults;
    private ?string $pageToken;
    private ?int $minExpirationDays;

    public function __construct()
    {
        $this->merchantId = config('feeds-google.merchant.id') ?? '';
        $this->maxResults = 250;
        $this->pageToken = null;
    }

    public static function run($minExpirationDays = null): bool
    {
        $self = new self();
        $self->minExpirationDays = $minExpirationDays;

        if (!$self->merchantId || !$self->minExpirationDays) {
            return false;
        }

        do {
            $productsStatuses = $self->getProductsStatuses();
            $self->renewProductExpiration($productsStatuses);
        } while ($self->pageToken !== null);

        return true;
    }

    private function getProductsStatuses()
    {
        $productsStatuses = GoogleServiceBuilder::googleMerchant()
            ->productstatuses
            ->listProductstatuses($this->merchantId, [
                'maxResults' => $this->maxResults,
                'pageToken' => $this->pageToken,
            ]);

        $this->pageToken = $productsStatuses->getNextPageToken() ?? null;

        return $productsStatuses->getResources();
    }

    private function renewProductExpiration($productsStatuses = [])
    {
        foreach ($productsStatuses as $productStatus) {

            $googleExpirationDate = Carbon::parse($productStatus->getGoogleExpirationDate());

            if (Carbon::now()->diffInDays($googleExpirationDate) < $this->minExpirationDays) {
                $productId = $this->cleanProductId($productStatus->getProductId());
                event(new ProductPublished($productId));
            }
        }
    }

    private function cleanProductId(string $productId): int
    {
        return str_replace('online:es:ES:', '', $productId);
    }
}
