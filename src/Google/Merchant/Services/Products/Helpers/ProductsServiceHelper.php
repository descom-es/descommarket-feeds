<?php

namespace DescomMarket\Feeds\Google\Merchant\Services\Products\Helpers;

use Illuminate\Support\Str;

final class ProductsServiceHelper
{
    // private array $categoriesOfAlcoholicBeverages = [10001, 10002, 10003, 10004]; // TODO remove form here

    public static function transformData(array $productData): array
    {
        $data = [
            'id' => $productData['id'],
            'offerId' => $productData['id'],
            'channel' => 'online',
            'targetCountry' => 'ES',
            'contentLanguage' => 'es',
            'title' => $productData['name'],
            'description' => (string)Str::of(html_entity_decode(strip_tags($productData['description'])))->limit(5000),
            'link' => $productData['url'],
            'image_link' => $productData['image']['url'],
            'availability' => $productData['in_stock'] ? 'in stock' : 'out of stock',
            'price' => [
                'value' => $productData['price'],
                'currency' => 'EUR',
            ],
            'shipping' => [
                'country' => 'ES',
                'price' => [
                    'value' => self::shippingCost($productData),
                    'currency' => 'EUR',
                ],
            ],
        ];

        $offer = self::offer($productData);
        if (! is_null($offer)) {
            $data['sale_price'] = [
                'value' => $offer,
                'currency' => 'EUR',
            ];
        }

        if (isset($productData['brand']['name'])) {
            $data['brand'] = $productData['brand']['name'];
        }

        if (! empty($productData['gtin'])) {
            $data['gtin'] = $productData['gtin'];
        }

        // $me = new self();

        // $categoryInGoogle = $me->getGoogleProductCategory(array_map(
        //     fn ($category) => $category['id'],
        //     $productData['categories']
        // ));

        // if ($categoryInGoogle) {
        //     $data['google_product_category'] = $categoryInGoogle;
        // }

        return $data;
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

    // private function getGoogleProductCategory(array $categoriesIdInDM): ?string
    // {
    //     $isAlcoholicBeverages = array_intersect($categoriesIdInDM, $this->categoriesOfAlcoholicBeverages) ? true : false;

    //     if ($isAlcoholicBeverages) {
    //         return '499676';
    //     }

    //     return null;
    // }
}
