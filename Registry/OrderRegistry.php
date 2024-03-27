<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Registry;

use Klevu\Registry\Api\OrderRegistryInterface;
use Magento\Sales\Api\Data\OrderInterface;

class OrderRegistry implements OrderRegistryInterface
{
    /**
     * @var OrderInterface|null
     */
    private ?OrderInterface $currentOrder = null;

    /**
     * @param OrderInterface $currentOrder
     * @return void
     */
    public function setCurrentOrder(OrderInterface $currentOrder): void
    {
        $this->currentOrder = $currentOrder;
    }

    /**
     * @return OrderInterface|null
     */
    public function getCurrentOrder(): ?OrderInterface
    {
        return $this->currentOrder;
    }
}
