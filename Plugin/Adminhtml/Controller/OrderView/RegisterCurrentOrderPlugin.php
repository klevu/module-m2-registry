<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Plugin\Adminhtml\Controller\OrderView;

use Klevu\Registry\Api\OrderRegistryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Controller\Adminhtml\Order\View as OrderViewController;

class RegisterCurrentOrderPlugin
{
    /**
     * @var OrderRegistryInterface
     */
    private OrderRegistryInterface $orderRegistry;
    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @param OrderRegistryInterface $orderRegistry
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(
        OrderRegistryInterface $orderRegistry,
        OrderRepositoryInterface $orderRepository,
    ) {
        $this->orderRegistry = $orderRegistry;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param OrderViewController $subject
     * @return mixed[]
     */
    public function beforeExecute(
        OrderViewController $subject
    ): array {
        $return = [];
        $request = $subject->getRequest();
        $orderId = (int)$request->getParam('order_id');
        if (!$orderId) {
            return $return;
        }

        try {
            $order = $this->orderRepository->get($orderId);
        } catch (NoSuchEntityException) {
            return $return;
        }

        $this->orderRegistry->setCurrentOrder($order);

        return $return;
    }
}
