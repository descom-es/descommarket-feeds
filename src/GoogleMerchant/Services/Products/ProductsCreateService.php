<?php

namespace Descom\DescomMarket\Feeds\GoogleMerchant\Services\Products;

use Descom\B2b\Core\App\Feeds\GoogleMerchant\GoogleMerchantConnection;

class ProductsCreateService extends GoogleMerchantConnection
{
    public function create(array $productData)
    {
        $response = $this->client->post('products', $this->transformData($productData));
        return $response->getBody()->getContents();
    }

    private function transformData(array $productData): array
    {
        $data = [
            'id'                      => $productData['id'], // 'required|max:50|alpha_num:ascii', //SKU
            'title'                   => $productData['id'], // 'required|max:150', //Nombre del producto
            'description'             => $productData['id'], // 'required|max:5000', //Admite HTML básico (ul, li, string, br, ...)
            'link'                    => $productData['id'], // 'required|max:2000|url', //URL del producto
            'image_link'              => $productData['id'], // 'required|max:2000|url', //URL de la imagen del producto, formatos soportados: JPEG (.jpg/.jpeg), WebP (.webp), PNG (.png), GIF (.gif), BMP (.bmp), and TIFF (.tif/.tiff))
            'additional_image_link'   => $productData['id'], // 'max:2000', //Máximo 10 urls, separadas por comas
            'availability'            => $productData['id'], // 'required|in:in stock,out of stock',
            'price'                   => $productData['id'], // 'required|regex:/^\d+(\.\d{1,2})?\s[A-Z]{3}$/', //Precio del producto, formato: 123.45 EUR
            'google_product_category' => $productData['id'], // 'numeric', //Id de la categoría de Google, ver https://support.google.com/merchants/answer/6324436?hl=es
            'brand'                   => $productData['id'], // 'required|max:70', //Marca del producto
            'gtin'                    => $productData['id'], // 'required|max:50|numeric', //Código de barras
            'mpn'                     => $productData['id'], // 'max:70|alpha_num:ascii', //Código del producto
        ];

        return $data;
    }
}
