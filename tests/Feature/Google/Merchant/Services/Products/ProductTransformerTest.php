<?php

namespace DescomMarket\Feeds\Tests\Feature\Google\Merchant\Services\Products;

use DescomMarket\Feeds\Google\Merchant\Services\Products\Transformer\ProductTransformer;
use DescomMarket\Feeds\Tests\TestCase;
use Google\Shopping\Merchant\Products\V1\Availability;
use Google\Shopping\Merchant\Products\V1\Condition;
use Google\Shopping\Type\Destination\DestinationEnum;

class ProductTransformerTest extends TestCase
{
    public function testIdentificaElProductoSinCanalYConElFeedLabel()
    {
        $productInput = ProductTransformer::transform($this->productData());

        $this->assertEquals('1234', $productInput->getOfferId());
        $this->assertEquals('es', $productInput->getContentLanguage());
        $this->assertEquals('ES', $productInput->getFeedLabel());
    }

    public function testConvierteLosPreciosAMicros()
    {
        $attributes = ProductTransformer::transform($this->productData())->getProductAttributes();

        $this->assertEquals(12340000, $attributes->getPrice()->getAmountMicros());
        $this->assertEquals('EUR', $attributes->getPrice()->getCurrencyCode());

        $this->assertEquals(4950000, $attributes->getShipping()[0]->getPrice()->getAmountMicros());
        $this->assertEquals('ES', $attributes->getShipping()[0]->getCountry());
    }

    public function testLaOfertaSeEnviaComoSalePrice()
    {
        $attributes = ProductTransformer::transform($this->productData([
            'offers' => [['price' => 9.99]],
        ]))->getProductAttributes();

        $this->assertEquals(9990000, $attributes->getSalePrice()->getAmountMicros());
    }

    public function testSinOfertaNoHaySalePrice()
    {
        $attributes = ProductTransformer::transform($this->productData())->getProductAttributes();

        $this->assertNull($attributes->getSalePrice());
    }

    public function testLaDisponibilidadEsUnEnum()
    {
        $enStock = ProductTransformer::transform($this->productData())->getProductAttributes();
        $sinStock = ProductTransformer::transform($this->productData(['in_stock' => false]))->getProductAttributes();

        $this->assertEquals(Availability::IN_STOCK, $enStock->getAvailability());
        $this->assertEquals(Availability::OUT_OF_STOCK, $sinStock->getAvailability());
    }

    public function testElEstadoEsUnEnumYPorDefectoEsNuevo()
    {
        $porDefecto = ProductTransformer::transform($this->productData())->getProductAttributes();
        $usado = ProductTransformer::transform($this->productData(['condition' => 'used']))->getProductAttributes();

        $this->assertEquals(Condition::PBNEW, $porDefecto->getCondition());
        $this->assertEquals(Condition::USED, $usado->getCondition());
    }

    public function testElGtinViajaComoLista()
    {
        $attributes = ProductTransformer::transform($this->productData(['gtin' => '8412345678905']))->getProductAttributes();

        $this->assertEquals(['8412345678905'], iterator_to_array($attributes->getGtins()));
    }

    public function testUnProductoSinMarcaNiGtinNoRevienta()
    {
        $attributes = ProductTransformer::transform($this->productData())->getProductAttributes();

        $this->assertEquals('', $attributes->getBrand());
        $this->assertCount(0, $attributes->getGtins());
    }

    public function testExcluirDeAnunciosUsaLosDestinosComoEnum()
    {
        $attributes = ProductTransformer::transform($this->productData(['excludeAds' => true]))->getProductAttributes();

        $this->assertEquals([
            DestinationEnum::SHOPPING_ADS,
            DestinationEnum::DISPLAY_ADS,
            DestinationEnum::LOCAL_INVENTORY_ADS,
        ], iterator_to_array($attributes->getExcludedDestinations()));
    }

    public function testLaDescripcionSeLimpiaYSeRecorta()
    {
        $attributes = ProductTransformer::transform($this->productData([
            'description' => '<p>Hola &amp; adi' . str_repeat('o', 1200) . 's</p>',
        ]))->getProductAttributes();

        $this->assertStringStartsWith('Hola & adio', $attributes->getDescription());
        $this->assertStringNotContainsString('<p>', $attributes->getDescription());

        // Str::limit corta a 1000 y añade los puntos suspensivos
        $this->assertStringEndsWith('...', $attributes->getDescription());
        $this->assertEquals(1003, mb_strlen($attributes->getDescription()));
    }

    public function testLasCategoriasFormanElProductType()
    {
        $attributes = ProductTransformer::transform($this->productData())->getProductAttributes();

        $this->assertEquals(['Herramientas > Manuales'], iterator_to_array($attributes->getProductTypes()));
    }

    private function productData(array $overrides = []): array
    {
        return array_merge([
            'id' => 1234,
            'sku' => 'SKU-1234',
            'name' => 'Destornillador',
            'description' => 'Un destornillador',
            'url' => 'https://example.com/p/destornillador',
            'image' => ['url' => 'https://example.com/i/destornillador.jpg'],
            'in_stock' => true,
            'price' => '12.34',
            'shipping_details' => ['price_with_tax' => 4.95],
            'categories' => [
                ['name' => 'Herramientas'],
                ['name' => 'Manuales'],
            ],
        ], $overrides);
    }
}
