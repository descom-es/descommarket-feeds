<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products\Transformer;

use Google\Shopping\Merchant\Products\V1\Availability;
use Google\Shopping\Merchant\Products\V1\Condition;
use Google\Shopping\Merchant\Products\V1\ProductAttributes;
use Google\Shopping\Merchant\Products\V1\ProductInput;
use Google\Shopping\Merchant\Products\V1\Shipping;
use Google\Shopping\Type\Destination\DestinationEnum;
use Google\Shopping\Type\Price;
use Illuminate\Support\Str;

final class ProductTransformer
{
    private const CURRENCY = 'EUR';

    public static function transform(array $productData): ProductInput
    {
        $attributes = new ProductAttributes();

        $attributes->setTitle($productData['name']);

        if ($productData['categoryInGoogleMerchant'] ?? null) {
            $attributes->setGoogleProductCategory($productData['categoryInGoogleMerchant']);
        }

        if ($productData['customLabel0'] ?? null) {
            $attributes->setCustomLabel0($productData['customLabel0']);
        }

        $attributes->setDescription((string) Str::of(html_entity_decode(strip_tags($productData['description'])))->limit(1000));
        $attributes->setLink($productData['url']);
        $attributes->setImageLink($productData['image']['url']);
        $attributes->setAvailability($productData['in_stock'] ? Availability::IN_STOCK : Availability::OUT_OF_STOCK);

        $attributes->setProductTypes([self::productType($productData)]);

        $attributes->setCondition(self::condition($productData['condition'] ?? 'new'));

        // Los setters de protobuf no admiten null, al contrario que los del
        // cliente antiguo.
        if ($productData['brand']['name'] ?? null) {
            $attributes->setBrand($productData['brand']['name']);
        }

        if ($productData['gtin'] ?? null) {
            $attributes->setGtins([$productData['gtin']]);
        }

        $attributes->setPrice(self::price($productData['price']));

        $shipping = new Shipping();
        $shipping->setCountry('ES');
        $shipping->setPrice(self::price(self::shippingCost($productData)));

        $attributes->setShipping([$shipping]);

        $offer = self::offer($productData);

        if (! is_null($offer)) {
            $attributes->setSalePrice(self::price($offer));
        }

        if ($productData['excludeAds'] ?? false) {
            $attributes->setExcludedDestinations([
                DestinationEnum::SHOPPING_ADS,
                DestinationEnum::DISPLAY_ADS,
                DestinationEnum::LOCAL_INVENTORY_ADS,
            ]);
        }

        $productInput = new ProductInput();

        $productInput->setOfferId((string) $productData['id']);
        $productInput->setContentLanguage(config('feeds-google.merchant.content_language', 'es'));
        $productInput->setFeedLabel(config('feeds-google.merchant.feed_label', 'ES'));
        $productInput->setProductAttributes($attributes);

        return $productInput;
    }

    /**
     * Merchant API pide el importe en micros, no la cadena "12.34" de la
     * Content API.
     */
    private static function price(string|float|int $amount): Price
    {
        $price = new Price();

        $price->setAmountMicros((int) round(((float) $amount) * 1000000));
        $price->setCurrencyCode(self::CURRENCY);

        return $price;
    }

    private static function condition(string $condition): int
    {
        return match ($condition) {
            'used' => Condition::USED,
            'refurbished' => Condition::REFURBISHED,
            default => Condition::PBNEW,
        };
    }

    private static function offer($productData): ?float
    {
        return $productData['offers'][0]['price'] ?? null;
    }

    private static function shippingCost($productData): float
    {
        $price = $productData['shipping_details']['price_with_tax'] ?? 0;

        return (float) number_format($price, 2, '.', '');
    }

    private static function productType($productData): string
    {
        return collect($productData['categories'])
            ->map(fn ($category) => $category['name'])
            ->implode(' > ');
    }
}
