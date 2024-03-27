<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Observer;

use Klevu\Registry\Api\ProductRegistryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RegisterCurrentProductObserver implements ObserverInterface
{
    /**
     * @var ProductRegistryInterface
     */
    private ProductRegistryInterface $productRegistry;

    /**
     * RegisterCurrentProductObserver constructor.
     *
     * @param ProductRegistryInterface $productRegistry
     */
    public function __construct(ProductRegistryInterface $productRegistry)
    {
        $this->productRegistry = $productRegistry;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $product = $observer->getDataUsingMethod('product');

        if (!($product instanceof ProductInterface)) {
            return;
        }
        $this->productRegistry->setCurrentProduct($product);
    }
}
