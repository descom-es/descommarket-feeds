<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products\Transformer;

use Closure;
use Google\Service\ShoppingContent\Price;
use Google\Service\ShoppingContent\Product;
use Illuminate\Support\Str;

final class ProductTransformer
{
    public static function transform(array $productData): Product
    {
        $product = new Product();

        $categoryInGoogleMerchant = $productData['categoryInGoogleMerchant'] ?? null;

        $product->setId($productData['sku']);
        $product->setOfferId($productData['id']);
        $product->setChannel('online');
        $product->setTargetCountry('ES');
        $product->setContentLanguage('es');
        $product->setTitle($productData['name']);

        if ($productData['categoryInGoogleMerchant'] ?? null) {
            $product->setGoogleProductCategory($productData['categoryInGoogleMerchant']);
        }

        if ($productData['customLabel0'] ?? null) {
            $product->setCustomLabel0($productData['customLabel0']);
        }

        $product->setDescription((string)Str::of(html_entity_decode(strip_tags($productData['description'])))->limit(1000));
        $product->setLink($productData['url']);
        $product->setImageLink($productData['image']['url']);
        $product->setAvailability($productData['in_stock'] ? 'in stock' : 'out of stock');

        $product->setProductTypes([self::productType($productData)]);

        $product->setCondition('new');
        $product->setBrand($productData['brand']['name'] ?? null);
        $product->setGtin($productData['gtin'] ?? null);

        $price = new Price();
        $price->setValue($productData['price']);
        $price->setCurrency('EUR');
        $product->setPrice($price);

        $product->setShipping([
            'country' => 'ES',
            'price' => [
                'value' => self::shippingCost($productData),
                'currency' => 'EUR',
            ],
        ]);

        $offer = self::offer($productData);

        if (! is_null($offer)) {
            $price = new Price();
            $price->setValue($offer);
            $price->setCurrency('EUR');
            $product->setSalePrice($price);
        }

        return $product;
    }

    private function transformerDriver(): Closure
    {
        return self::$transformerDriver ?? function ($productData) {
            return $productData;
        };
    }

    private static function offer($productData): ?float
    {
        return $productData['offers'][0]['price'] ?? null;
    }

    private static function shippingCost($productData): float
    {
        $price = $productData['shipping_details']['price_with_tax'] ?? 0;

        return (float)number_format($price, 2, '.', '');
    }

    private static function productType($productData): string
    {
        return collect($productData['categories'])
            ->map(fn ($category) => $category['name'])
            ->implode(' > ');
    }
}
