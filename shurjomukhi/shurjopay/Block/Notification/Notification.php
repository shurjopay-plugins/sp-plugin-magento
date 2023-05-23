<?php

/**
 * Magento 2 plugin to provide shurjoPay gateway services.
 *
 * @package shurjomukhi\shurjopay\Block\Notification
 * @category Magento\Plugin
 * @module Shurjopay
 * @author Md Wali Mosnad Ayshik
 * @since 2023/05/22
 */

namespace shurjomukhi\shurjopay\Block\Notification;

use Magento\Framework\View\Element\Template;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order\Config as OrderConfig;
use Magento\Framework\App\Http\Context;
use shurjomukhi\shurjopay\Model\Shurjopay;

class Notification extends Template
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var OrderFactory
     */
    protected $orderFactory;

    /**
     * @var OrderConfig
     */
    protected $orderConfig;

    /**
     * @var Context
     */
    protected $httpContext;

    /**
     * @var Shurjopay
     */
    protected $paymentConfig;

    /**
     * Notification constructor.
     *
     * @param Template\Context $context
     * @param CheckoutSession $checkoutSession
     * @param CustomerSession $customerSession
     * @param OrderFactory $orderFactory
     * @param OrderConfig $orderConfig
     * @param Context $httpContext
     * @param Shurjopay $paymentConfig
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession,
        OrderFactory $orderFactory,
        OrderConfig $orderConfig,
        Context $httpContext,
        Shurjopay $paymentConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
        $this->orderFactory = $orderFactory;
        $this->orderConfig = $orderConfig;
        $this->httpContext = $httpContext;
        $this->paymentConfig = $paymentConfig;
    }

    /**
     * Get the template file
     *
     * @var string
     */
    protected $_template = 'success/success.phtml';

    /**
     * Get the success message from the payment configuration
     *
     * @return string|null
     */
    public function getSuccessMsg()
    {
        return $this->paymentConfig->getSuccessMsg();
    }
}
