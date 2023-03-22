<?php

namespace DescomMarket\Feeds\GoogleMerchant\Services\Products\Helpers;

final class ProductsServiceHelper
{
    /**
     * @param array $productData
     * 'id' => 'required|max:50|alpha_num:ascii', //SKU
     * 'title' => 'required|max:150', //Nombre del producto
     * 'description' => 'required|max:5000', //Admite HTML básico (ul, li, string, br, ...)
     * 'link' => 'required|max:2000|url', //URL del producto
     * 'image_link' => 'required|max:2000|url', //URL de la imagen del producto, formatos soportados: JPEG (.jpg/.jpeg), WebP (.webp), PNG (.png), GIF (.gif), BMP (.bmp), and TIFF (.tif/.tiff))
     * 'additional_image_link' => 'max:2000', //Máximo 10 urls, separadas por comas
     * 'availability' => 'required|in:in stock,out of stock',
     * 'price' => 'required|regex:/^\d+(\.\d{1,2})?\s[A-Z]{3}$/', //Precio del producto, formato: 123.45 EUR
     * 'google_product_category' => 'numeric', //Id de la categoría de Google, ver https://support.google.com/merchants/answer/6324436?hl=es
     * 'brand' => 'required|max:70', //Marca del producto
     * 'gtin' => 'required|max:50|numeric', //Código de barras
     * 'mpn' => 'max:70|alpha_num:ascii', //Código del producto
     * @return array
     */
    public static function transformData(array $productData): array
    {
        $data = [];

        $data['id'] = $productData['sku'];
        $data['title'] = $productData['name'];
        $data['description'] = self::transformDescription($productData['description']);
        $data['link'] = config('b2b-core.shop.url') . $productData['url_path']; //TODO: Estamos cogiendo del config del B2B, ¿dónde deberíamos definirlo en el common?
        $data['image_link'] = $productData['image']['url'];
        $data['additional_image_link'] = self::transformAdditionalImageLink($productData['gallery']);
        $data['availability'] = $productData['in_stock'] ? 'in stock' : 'out of stock';
        $data['price'] = [
            'value' => $productData['price'],
            'currency' => 'EUR',
        ];
        if (self::existsOfferPrice($productData['price'], $productData['offers'] ?? [])) {
            $data['sale_price'] = [
                'value' => $productData['offers'][0]['price'],
                'currency' => 'EUR',
            ];
        }
        if (isset($productData['brand']['name'])) {
            $data['brand'] = $productData['brand']['name'];
        }

        // $data['gtin'] = ???;
        // $data['mpn'] = ???;
        // $data['google_product_category'] = ???;

        return $data;
    }

    private static function transformDescription(string $description): string
    {
        if (strlen($description) > 5000) {
            return substr(strip_tags($description), 0, 5000);
        }

        return $description;
    }

    private static function transformAdditionalImageLink(array $gallery): string
    {
        return implode(',', array_map(function ($image) {
            return $image['url'];
        }, $gallery));
    }

    private static function existsOfferPrice($price, array $offers): bool
    {
        return isset($productData['offers']) && isset($productData['offers'][0]) && isset($productData['offers'][0]['price']) && $productData['offers'][0]['price'] != $price;
    }
}
