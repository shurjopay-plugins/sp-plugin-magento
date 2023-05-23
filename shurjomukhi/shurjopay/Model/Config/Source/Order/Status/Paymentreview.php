<?php
/**
 * shurjomukhi\shurjopay\Model\Config\Source\Order\Status\Paymentreview
 *
 * @category   shurjomukhi
 * @package    shurjomukhi\shurjopay
 * @subpackage Model\Config\Source\Order\Status
 * @version    1.0.0
 * @since      2023/05/22
 * @author     Md Wali Mosnad Ayshik
 * @see        Magento\Sales\Model\Order
 * @see        Magento\Sales\Model\Config\Source\Order\Status
 */

namespace shurjomukhi\shurjopay\Model\Config\Source\Order\Status;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Config\Source\Order\Status;

/**
 * Order Status source model for Payment Review
 */
class Paymentreview extends Status
{
    /**
     * @var string[]
     */
    protected $_stateStatuses = [Order::STATE_PAYMENT_REVIEW];
}
