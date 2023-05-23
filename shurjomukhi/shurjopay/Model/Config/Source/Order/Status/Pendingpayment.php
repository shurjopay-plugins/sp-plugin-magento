<?php
/**
 * Magento 2 module to provide ShurjoPay payment gateway integration.
 *
 * @package shurjomukhi\shurjopay\Model\Config\Source\Order\Status
 * @category Magento\Module
 * @module Shurjopay
 * @author Md Wali Mosnad Ayshik
 * @since 2023/05/22
 */

namespace shurjomukhi\shurjopay\Model\Config\Source\Order\Status;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Config\Source\Order\Status;

/**
 * Order Status source model for "Pending Payment" status
 */
class PendingPayment extends Status
{
    /**
     * @var string[]
     */
    protected $_stateStatuses = [Order::STATE_PENDING_PAYMENT];
}
