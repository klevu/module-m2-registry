<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\ViewModel;

use Klevu\Registry\Api\OrderRegistryInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Api\Data\OrderInterface;

class CurrentOrder implements ArgumentInterface
{
    /**
     * @var OrderRegistryInterface
     */
    private readonly OrderRegistryInterface $orderRegistry;

    /**
     * @param OrderRegistryInterface $orderRegistry
     */
    public function __construct(
        OrderRegistryInterface $orderRegistry,
    ) {
        $this->orderRegistry = $orderRegistry;
    }

    /**
     * @return OrderInterface|null
     */
    public function getCurrentOrder(): ?OrderInterface
    {
        return $this->orderRegistry->getCurrentOrder();
    }
}
