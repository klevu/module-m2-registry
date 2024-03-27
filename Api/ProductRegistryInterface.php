<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Api;

use Magento\Catalog\Api\Data\ProductInterface;

interface ProductRegistryInterface
{
    /**
     * @param ProductInterface $currentProduct
     *
     * @return void
     */
    public function setCurrentProduct(ProductInterface $currentProduct): void;

    /**
     * @return ProductInterface|null
     */
    public function getCurrentProduct(): ?ProductInterface;
}
