<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products\Transformer;

use Google\Service\ShoppingContent\Price;
use Google\Service\ShoppingContent\Product;
use Illuminate\Support\Str;

final class ProductTransformer
{
    public static function transform(array $productData): Product
    {
        $product = new Product();

        $product->setId($productData['sku']);
        $product->setOfferId($productData['id']);
        $product->setChannel('online');
        $product->setTargetCountry('ES');
        $product->setContentLanguage('es');
        $product->setTitle($productData['name']);
        $product->setDescription((string)Str::of(html_entity_decode(strip_tags($productData['description'])))->limit(5000));
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

        $sectionSlug = $productData['categories'][0]['slug'] ?? null;
        $product->setCustomLabel0($sectionSlug);

        return $product;
    }

    private static function offer($productData): ?float
    {
        return $productData['offers'][0]['price'] ?? null;
    }

    private static function shippingCost($productData): float
    {
        $price = (float)($productData['extra']['data']['lowest_shipping_cost'] ?? 0) * 1.21;

        return (float)number_format($price, 2, '.', '');
    }

    private static function productType($productData): string
    {
        return collect($productData['categories'])
            ->map(fn ($category) => $category['name'])
            ->implode(' > ');
    }
}
