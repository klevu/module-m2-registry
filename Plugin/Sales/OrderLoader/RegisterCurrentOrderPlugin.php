<?php

/**
 * Copyright © Klevu Oy. All rights reserved. See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Klevu\Registry\Plugin\Sales\OrderLoader;

use Klevu\Registry\Api\OrderRegistryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Controller\AbstractController\OrderLoader;

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
     * @param OrderLoader $subject
     * @param RequestInterface $request
     * @return RequestInterface[]
     */
    public function beforeLoad(
        OrderLoader $subject,
        RequestInterface $request,
    ): array {
        $return = [$request];
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
