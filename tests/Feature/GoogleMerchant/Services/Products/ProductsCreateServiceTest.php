<?php

namespace DescomMarket\Feeds\Tests\Feature\GoogleMerchant\Services\Products;

use DescomMarket\Feeds\GoogleMerchant\Services\Products\Helpers\ProductsServiceHelper;
use DescomMarket\Feeds\Tests\TestCase;

class ProductsCreateServiceTest extends TestCase
{
    private array $productData;

    public function setUp(): void
    {
        parent::setUp();

        $this->productData = $this->getProductdata();
    }

    public function testCreate()
    {
        //
    }

    private function getProductdata(): array
    {
        $productData = [
            "id" => 1,
            "sku" => "V0100452",
            "name" => "Decantador de Vino InnovaGoods",
            "slug" => "p-decantador-de-vino-innovagoods-1",
            "url_path" => "/c-bebidas-y-accesorios-10000/p-decantador-de-vino-innovagoods-1",
            "attribute_set" => [
                "id" => 1,
                "name" => "default"
            ],
            "type" => "simple",
            "price" => 14.9,
            "price_base" => 12.31,
            "offers" => [
                [
                    "price" => 10.51,
                    "price_base" => 8.69,
                    "discountPercent" => 29
                ]
            ],
            "in_stock" => false,
            "quantity" => 0,
            "brand" => [
                "id" => 1013,
                "name" => "InnovaGoods",
                "slug" => "b-innovagoods-1013"
            ],
            "image" => [
                "url" => "https://cdn-stage.yapayoo.com/images/p-1-1.jpg"
            ],
            "categories" => [
                [
                    "id" => 10000,
                    "name" => "Bebidas y accesorios",
                    "slug" => "c-bebidas-y-accesorios-10000",
                    "order" => 0,
                    "path" => "9999/10000",
                    "depth" => 1,
                    "url_path" => "/c-bebidas-y-accesorios-10000",
                    "extra" => null
                ],
                [
                    "id" => 10005,
                    "name" => "Accesorios",
                    "slug" => "c-accesorios-10005",
                    "order" => 0,
                    "path" => "9999/10000/10005",
                    "depth" => 2,
                    "url_path" => "/c-bebidas-y-accesorios-10000/c-accesorios-10005",
                    "extra" => null
                ],
                [
                    "id" => 10007,
                    "name" => "Jarras",
                    "slug" => "c-jarras-10007",
                    "order" => 0,
                    "path" => "9999/10000/10005/10007",
                    "depth" => 3,
                    "url_path" => "/c-bebidas-y-accesorios-10000/c-jarras-10007",
                    "extra" => null
                ]
            ],
            "labels" => [
                [
                    "id" => 75,
                    "name" => "San Valentín"
                ]
            ]
        ];

        $productExtendedData = [

            "id" => 1,
            "sku" => "V0100452",
            "description" => "¡<b>InnovaGoods</b> viene en tu ayuda para cocinar de forma fácil y divertida ofreciéndote las mejores novedades para tu cocina, como <b>Decantador de Vino InnovaGoods </b>! ¡Descubre una amplia gama de productos de calidad que destacan por su funcionalidad, eficacia e innovador diseño!<br><p>Ideal para potenciar al máximo el sabor y las propiedades del vino para que aflore su auténtico aroma, ya que permite limpiar, airear y oxigenar el vino para servirlo en óptimas condiciones. Un regalo perfecto para los amantes del buen vino. También es de gran utilidad en vinotecas, bares, restaurantes, etc.</p><br><ul><li>Material: <ul><li>AS</li><li>ABS</li><li>Silicona</li></ul></li><li>Color: <ul><li>Negro</li><li>Transparente</li></ul></li><li>Diseño moderno: Innovador y funcional</li><li>Tipo: Multifunción</li><li>Estilo exclusivo y elegante: Ideal para regalar</li><li>Incluye: <ul><li>Filtro para posos o restos de corcho</li><li>Base de soporte y antigoteo</li></ul></li><li>Fácil de usar: Uso cómodo y sencillo</li><li>Compacto: Ocupa poco espacio</li><li>Bolsa de transporte: Fácil de transportar y guardar</li><li>Medidas aprox.: 5,5 x 16 x 7 cm</li><li>Packaging en 24 idiomas: inglés, francés, español, alemán, italiano, portugués, holandés, polaco, húngaro, rumano, danés, sueco, finés, lituano, noruego, esloveno, griego, checo, búlgaro, croata, eslovaco, estonio, ruso, letón</li></ul>",
            "gallery" => [
                [
                    "url" => "https://cdn-stage.yapayoo.com/images/p-2-2.jpg"
                ],
                [
                    "url" => "https://cdn-stage.yapayoo.com/images/p-3-3.jpg"
                ],
                [
                    "url" => "https://cdn-stage.yapayoo.com/images/p-4-4.jpg"
                ],
                [
                    "url" => "https://cdn-stage.yapayoo.com/images/p-5-5.jpg"
                ],
                [
                    "url" => "https://cdn-stage.yapayoo.com/images/p-6-6.jpg"
                ],
                [
                    "url" => "https://cdn-stage.yapayoo.com/images/p-7-7.jpg"
                ]
            ],
            "extra" => [
                "data" => [
                    "weight" => "0.305",
                    "height" => "18",
                    "width" => "6.5",
                    "depth" => "8"
                ]
            ],
            "resources_libraries" => [
                "videos" => [
                    [
                        "id" => "e1578385-32a0-4bac-81ae-cf851e8fc2d8",
                        "name" => null,
                        "type" => "video",
                        "collection" => "videos",
                        "custom_properties" => [
                            "source" => "youtube",
                            "id" => "Atq4idLTo0Y"
                        ],
                        "url" => "http://localhost:8000/resource-libraries/e1578385-32a0-4bac-81ae-cf851e8fc2d8/"
                    ]
                ]
            ],
            "related" => [
                "byLabel" => [
                    [
                        "id" => 75,
                        "name" => "San Valentín",
                        "products" => [
                            [
                                "id" => 1,
                                "sku" => "V0100452",
                                "name" => "Decantador de Vino InnovaGoods",
                                "slug" => "p-decantador-de-vino-innovagoods-1",
                                "url_path" => "/c-bebidas-y-accesorios-10000/p-decantador-de-vino-innovagoods-1",
                                "type" => "simple",
                                "price" => 14.9,
                                "price_base" => 12.31,
                                "offers" => [
                                    [
                                        "price" => 10.51,
                                        "price_base" => 8.69,
                                        "discountPercent" => 29
                                    ]
                                ],
                                "in_stock" => false,
                                "quantity" => 0,
                                "image" => [
                                    "url" => "https://cdn-stage.yapayoo.com/images/p-1-1.jpg"
                                ],
                                "categories" => [
                                    [
                                        "id" => 10000,
                                        "name" => "Bebidas y accesorios",
                                        "slug" => "c-bebidas-y-accesorios-10000",
                                        "order" => 0,
                                        "path" => "9999/10000",
                                        "depth" => 1,
                                        "url_path" => "/c-bebidas-y-accesorios-10000",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 10005,
                                        "name" => "Accesorios",
                                        "slug" => "c-accesorios-10005",
                                        "order" => 0,
                                        "path" => "9999/10000/10005",
                                        "depth" => 2,
                                        "url_path" => "/c-bebidas-y-accesorios-10000/c-accesorios-10005",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 10007,
                                        "name" => "Jarras",
                                        "slug" => "c-jarras-10007",
                                        "order" => 0,
                                        "path" => "9999/10000/10005/10007",
                                        "depth" => 3,
                                        "url_path" => "/c-bebidas-y-accesorios-10000/c-jarras-10007",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 20001,
                                        "name" => "tax_1",
                                        "slug" => "c-tax-1-20001",
                                        "order" => 0,
                                        "path" => "20000/20001",
                                        "depth" => 1,
                                        "url_path" => "/c-tax-1-20001",
                                        "extra" => null
                                    ]
                                ]
                            ],
                            [
                                "id" => 1467,
                                "sku" => "V0101246",
                                "name" => "Set de Accesorios para Vino Servin InnovaGoods 5 Piezas",
                                "slug" => "p-set-de-accesorios-para-vino-servin-innovagoods-5-piezas-1467",
                                "url_path" => "/c-bebidas-y-accesorios-10000/c-cocina-10047/p-set-de-accesorios-para-vino-servin-innovagoods-5-piezas-1467",
                                "type" => "simple",
                                "price" => 26.9,
                                "price_base" => 22.23,
                                "offers" => [
                                    [
                                        "price" => 19.02,
                                        "price_base" => 15.72,
                                        "discountPercent" => 29
                                    ]
                                ],
                                "in_stock" => false,
                                "quantity" => 0,
                                "image" => [
                                    "url" => "https://cdn-stage.yapayoo.com/images/p-2459-2459.jpg"
                                ],
                                "categories" => [
                                    [
                                        "id" => 10000,
                                        "name" => "Bebidas y accesorios",
                                        "slug" => "c-bebidas-y-accesorios-10000",
                                        "order" => 0,
                                        "path" => "9999/10000",
                                        "depth" => 1,
                                        "url_path" => "/c-bebidas-y-accesorios-10000",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 10005,
                                        "name" => "Accesorios",
                                        "slug" => "c-accesorios-10005",
                                        "order" => 0,
                                        "path" => "9999/10000/10005",
                                        "depth" => 2,
                                        "url_path" => "/c-bebidas-y-accesorios-10000/c-accesorios-10005",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 10010,
                                        "name" => "Utensilios de bar",
                                        "slug" => "c-utensilios-de-bar-10010",
                                        "order" => 0,
                                        "path" => "9999/10000/10005/10010",
                                        "depth" => 3,
                                        "url_path" => "/c-bebidas-y-accesorios-10000/c-utensilios-de-bar-10010",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 10047,
                                        "name" => "Cocina",
                                        "slug" => "c-cocina-10047",
                                        "order" => 0,
                                        "path" => "9999/10047",
                                        "depth" => 1,
                                        "url_path" => "/c-cocina-10047",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 10069,
                                        "name" => "Repostería",
                                        "slug" => "c-reposteria-10069",
                                        "order" => 0,
                                        "path" => "9999/10047/10069",
                                        "depth" => 2,
                                        "url_path" => "/c-cocina-10047/c-reposteria-10069",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 10081,
                                        "name" => "Accesorios",
                                        "slug" => "c-accesorios-10081",
                                        "order" => 0,
                                        "path" => "9999/10047/10069/10081",
                                        "depth" => 3,
                                        "url_path" => "/c-cocina-10047/c-accesorios-10081",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 10089,
                                        "name" => "Dispensadores de nata",
                                        "slug" => "c-dispensadores-de-nata-10089",
                                        "order" => 0,
                                        "path" => "9999/10047/10069/10081/10089",
                                        "depth" => 4,
                                        "url_path" => "/c-cocina-10047/c-dispensadores-de-nata-10089",
                                        "extra" => null
                                    ],
                                    [
                                        "id" => 20001,
                                        "name" => "tax_1",
                                        "slug" => "c-tax-1-20001",
                                        "order" => 0,
                                        "path" => "20000/20001",
                                        "depth" => 1,
                                        "url_path" => "/c-tax-1-20001",
                                        "extra" => null
                                    ]
                                ]
                            ]
                        ],
                        "meta" => []
                    ]
                ],
                "byCategory" => []
            ]
        ];

        return array_merge($productData, $productExtendedData);
    }
}
