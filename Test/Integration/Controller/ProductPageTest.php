<?php // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

/**
 * Copyright © Klevu. All rights reserved. See LICENSE.txt for license details.
 */

namespace Klevu\Registry\Test\Integration\Controller;

use Exception;
use Klevu\Registry\Api\CategoryRegistryInterface;
use Klevu\Registry\Api\ProductRegistryInterface;
use Klevu\Registry\Registry\ProductRegistry;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\CatalogUrlRewrite\Model\ProductUrlPathGenerator;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Store\Model\ScopeInterface;
use Magento\TestFramework\TestCase\AbstractController as AbstractControllerTestCase;
use TddWizard\Fixtures\Catalog\ProductBuilder;
use TddWizard\Fixtures\Catalog\ProductFixturePool;

/**
 * @covers ProductRegistry
 */
class ProductPageTest extends AbstractControllerTestCase
{
    /**
     * @var string|null
     */
    private ?string $urlSuffix;
    /**
     * @var ProductFixturePool|null
     */
    private ?ProductFixturePool $productFixtures;
    /**
     * @var ProductRegistryInterface|null
     */
    private ?ProductRegistryInterface $productRegistry;
    /**
     * @var CategoryRegistryInterface|null
     */
    private ?CategoryRegistryInterface $categoryRegistry;

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     */
    public function testRegistryValuesOnEnabledProduct(): void
    {
        $this->createProducts();

        $productFixture = $this->productFixtures->get('product1');
        $url = $this->prepareUrl($productFixture->getProduct()->getCustomAttribute('url_key')->getValue());

        $this->dispatch($url);

        /** @var HttpResponse $response */
        $response = $this->getResponse();

        $this->assertSame(200, $response->getHttpResponseCode());
        $this->assertNull($this->categoryRegistry->getCurrentCategory());
        $this->assertInstanceOf(ProductInterface::class, $this->productRegistry->getCurrentProduct());
        $this->assertSame('klevu_simple_1', $this->productRegistry->getCurrentProduct()->getSku());
    }

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     */
    public function testRegistryValuesOn404NotFound(): void
    {
        $this->createProducts();

        $this->dispatch('catalog/product/view/id/999999');

        $this->assert404NotFound();
        $this->assertNull($this->categoryRegistry->getCurrentCategory());
        $this->assertNull($this->productRegistry->getCurrentProduct());
    }

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     */
    public function testRegistryValuesOnDisabledProduct(): void
    {
        $this->createProducts();

        $productFixture = $this->productFixtures->get('product2');
        $url = $this->prepareUrl($productFixture->getProduct()->getCustomAttribute('url_key')->getValue());

        $this->dispatch($url);

        $this->assert404NotFound();
        $this->assertNull($this->categoryRegistry->getCurrentCategory());
        $this->assertNull($this->productRegistry->getCurrentProduct());
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $scopeConfig = $this->_objectManager->get(ScopeConfigInterface::class);
        $this->urlSuffix = $scopeConfig->getValue(
            ProductUrlPathGenerator::XML_PATH_PRODUCT_URL_SUFFIX,
            ScopeInterface::SCOPE_STORE
        );
        $this->productFixtures = new ProductFixturePool();
        $this->productRegistry = $this->_objectManager->get(ProductRegistryInterface::class);
        $this->categoryRegistry = $this->_objectManager->get(CategoryRegistryInterface::class);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->productFixtures->rollback();
    }

    /**
     * @return void
     * @throws Exception
     */
    private function createProducts(): void
    {
        $this->productFixtures->add(
            ProductBuilder::aSimpleProduct()
                ->withSku('klevu_simple_1')
                ->withStatus(Status::STATUS_ENABLED)
                ->build(),
            'product1'
        );
        $this->productFixtures->add(
            ProductBuilder::aSimpleProduct()
                ->withSku('klevu_simple_2')
                ->withStatus(Status::STATUS_DISABLED)
                ->build(),
            'product2'
        );
    }

    /**
     * Prepare url to dispatch
     *
     * @param string $urlKey
     * @param bool $addSuffix
     *
     * @return string
     */
    private function prepareUrl(string $urlKey, bool $addSuffix = true): string
    {
        return $addSuffix
            ? '/' . $urlKey . $this->urlSuffix
            : '/' . $urlKey;
    }
}
