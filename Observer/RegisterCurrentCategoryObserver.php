<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Observer;

use Klevu\Registry\Api\CategoryRegistryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class RegisterCurrentCategoryObserver implements ObserverInterface
{
    /**
     * @var CategoryRegistryInterface
     */
    private CategoryRegistryInterface $categoryRegistry;

    /**
     * RegisterCurrentCategoryObserver constructor.
     *
     * @param CategoryRegistryInterface $categoryRegistry
     */
    public function __construct(CategoryRegistryInterface $categoryRegistry)
    {
        $this->categoryRegistry = $categoryRegistry;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $category = $observer->getDataUsingMethod('category');
        if (!($category instanceof CategoryInterface)) {
            return;
        }
        $this->categoryRegistry->setCurrentCategory($category);
    }
}
