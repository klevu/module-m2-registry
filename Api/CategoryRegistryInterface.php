<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Api;

use Magento\Catalog\Api\Data\CategoryInterface;

interface CategoryRegistryInterface
{
    /**
     * @param CategoryInterface $currentCategory
     *
     * @return void
     */
    public function setCurrentCategory(CategoryInterface $currentCategory): void;

    /**
     * @return CategoryInterface|null
     */
    public function getCurrentCategory(): ?CategoryInterface;
}
