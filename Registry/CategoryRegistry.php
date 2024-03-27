<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Registry;

use Klevu\Registry\Api\CategoryRegistryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;

class CategoryRegistry implements CategoryRegistryInterface
{
    /**
     * @var CategoryInterface|null
     */
    private ?CategoryInterface $currentCategory = null;

    /**
     * @param CategoryInterface $currentCategory
     *
     * @return void
     */
    public function setCurrentCategory(CategoryInterface $currentCategory): void
    {
        $this->currentCategory = $currentCategory;
    }

    /**
     * @return CategoryInterface|null
     */
    public function getCurrentCategory(): ?CategoryInterface
    {
        return $this->currentCategory;
    }
}
