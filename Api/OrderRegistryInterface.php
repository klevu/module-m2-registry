<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Api;

use Magento\Sales\Api\Data\OrderInterface;

interface OrderRegistryInterface
{
    /**
     * @param OrderInterface $currentOrder
     * @return void
     */
    public function setCurrentOrder(OrderInterface $currentOrder): void;

    /**
     * @return OrderInterface|null
     */
    public function getCurrentOrder(): ?OrderInterface;
}
