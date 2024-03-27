<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Registry;

use Klevu\Registry\Api\ProductRegistryInterface;
use Magento\Catalog\Api\Data\ProductInterface;

class ProductRegistry implements ProductRegistryInterface
{
    /**
     * @var ProductInterface|null
     */
    private ?ProductInterface $currentProduct = null;

    /**
     * @param ProductInterface $currentProduct
     *
     * @return void
     */
    public function setCurrentProduct(ProductInterface $currentProduct): void
    {
        $this->currentProduct = $currentProduct;
    }

    /**
     * @return ProductInterface|null
     */
    public function getCurrentProduct(): ?ProductInterface
    {
        return $this->currentProduct;
    }
}
