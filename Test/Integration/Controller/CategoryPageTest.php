<?php // phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps

/**
 * Copyright © Klevu. All rights reserved. See LICENSE.txt for license details.
 */

namespace Klevu\Registry\Test\Integration\Controller;

use Klevu\Registry\Api\CategoryRegistryInterface;
use Klevu\Registry\Api\ProductRegistryInterface;
use Klevu\Registry\Registry\CategoryRegistry;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Store\Model\ScopeInterface;
use Magento\TestFramework\TestCase\AbstractController as AbstractControllerTestCase;
use TddWizard\Fixtures\Catalog\CategoryBuilder;
use TddWizard\Fixtures\Catalog\CategoryFixture;
use TddWizard\Fixtures\Catalog\CategoryFixturePool;

/**
 * @covers CategoryRegistry
 */
class CategoryPageTest extends AbstractControllerTestCase
{
    /**
     * @var ScopeConfigInterface|null
     */
    private ?ScopeConfigInterface $scopeConfig;
    /**
     * @var string|null
     */
    private ?string $urlSuffix;
    /**
     * @var CategoryFixturePool|null
     */
    private ?CategoryFixturePool $categoryFixtures;
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
    public function testRegistryValuesOnEnabledCategory(): void
    {
        $this->createCategories();

        $categoryTop = $this->categoryFixtures->get('top');
        $category1 = $this->categoryFixtures->get('category1');

        $url = $this->prepareUrl($categoryTop->getUrlKey() . '/' . $category1->getUrlKey());
        $this->dispatch($url);

        /** @var HttpResponse $response */
        $response = $this->getResponse();
        $currentCategory = $this->categoryRegistry->getCurrentCategory();

        $this->assertSame(200, $response->getHttpResponseCode());
        $this->assertInstanceOf(CategoryInterface::class, $currentCategory);
        $this->assertSame('[Klevu] Parent Category 1', $currentCategory->getName());
        $currentProduct = $this->productRegistry->getCurrentProduct();
        $this->assertNull($currentProduct);
    }

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     */
    public function testRegistryValuesOn404NotFound(): void
    {
        $this->createCategories();

        $this->dispatch('catalog/category/view/id/0');

        $this->assert404NotFound();
        $currentCategory = $this->categoryRegistry->getCurrentCategory();
        $this->assertNull($currentCategory);
        $currentProduct = $this->productRegistry->getCurrentProduct();
        $this->assertNull($currentProduct);
    }

    /**
     * @magentoAppArea frontend
     * @magentoCache all disabled
     */
    public function testRegistryValuesOnDisabledCategory(): void
    {
        $this->createCategories();

        $categoryTop = $this->categoryFixtures->get('top');
        $category2 = $this->categoryFixtures->get('category2');

        $url = $this->prepareUrl($categoryTop->getUrlKey() . '/' . $category2->getUrlKey());
        $this->dispatch($url);

        $this->assert404NotFound();
        $currentCategory = $this->categoryRegistry->getCurrentCategory();
        $this->assertNull($currentCategory);
        $currentProduct = $this->productRegistry->getCurrentProduct();
        $this->assertNull($currentProduct);
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->scopeConfig = $this->_objectManager->get(ScopeConfigInterface::class);
        $this->urlSuffix = $this->scopeConfig->getValue(
            CategoryUrlPathGenerator::XML_PATH_CATEGORY_URL_SUFFIX,
            ScopeInterface::SCOPE_STORE
        );
        $this->categoryFixtures = new CategoryFixturePool();
        $this->productRegistry = $this->_objectManager->get(ProductRegistryInterface::class);
        $this->categoryRegistry = $this->_objectManager->get(CategoryRegistryInterface::class);
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->categoryFixtures->rollback();
    }

    /**
     * @return void
     */
    private function createCategories(): void
    {
        $topCat = CategoryBuilder::topLevelCategory()->build();

        $categoryFixture = new CategoryFixture($topCat);

        $this->categoryFixtures->add($topCat, 'top');
        $this->categoryFixtures->add(
            CategoryBuilder::childCategoryOf($categoryFixture)
                ->withName('[Klevu] Parent Category 1')
                ->withUrlKey('klevu-test-category-1')
                ->withDescription('[Klevu Test Fixtures] Parent category 1')
                ->withIsActive(true)
                ->build(),
            'category1'
        );
        $this->categoryFixtures->add(
            CategoryBuilder::childCategoryOf($categoryFixture)
                ->withName('[Klevu] Parent Category 2')
                ->withUrlKey('klevu-test-category-2')
                ->withDescription('[Klevu Test Fixtures] Parent category 2')
                ->withIsActive(false)
                ->build(),
            'category2'
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
