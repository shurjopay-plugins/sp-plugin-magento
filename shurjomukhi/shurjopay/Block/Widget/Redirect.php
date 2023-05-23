<?php

/**
 * Magento 2 plugin to provide shurjoPay gateway services.
 *
 * @package shurjomukhi\shurjopay\Block\Widget
 * @category Magento\Plugin
 * @module Shurjopay
 * @author Md Wali Mosnad Ayshik
 * @since 2023/05/22
 */

namespace shurjomukhi\shurjopay\Block\Widget;

use Magento\Framework\View\Element\Template;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order\Config as OrderConfig;
use Magento\Framework\App\Http\Context;
use shurjomukhi\shurjopay\Model\Shurjopay;

class Redirect extends Template
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
     * Redirect constructor.
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
    protected $_template = 'html/rk.phtml';

    /**
     * Get the shurjoPay gateway URL
     *
     * @return string|null
     */
    public function getGateUrl()
    {
        return $this->paymentConfig->getGateUrl();
    }

    /**
     * Get the amount for the current order
     *
     * @return float|null
     */
    public function getAmount()
    {
        $orderId = $this->checkoutSession->getLastOrderId();
        if ($orderId) {
            $incrementId = $this->checkoutSession->getLastRealOrderId();

            return $this->paymentConfig->getAmount($incrementId);
        }
        return null;
    }

    /**
     * Get the post data for shurjoPay gateway
     *
     * @return array|null
     */
    public function getPostData()
    {
        $orderId = $this->checkoutSession->getLastOrderId();
        if ($orderId) {
            $incrementId = $this->checkoutSession->getLastRealOrderId();

            return $this->paymentConfig->getPostData($incrementId);
        }
        return null;
    }
    
    /**
     * Get the payment method code
     *
     * @return string|null
     */
    public function getPaymentMethod()
    {
        return $this->paymentConfig->getPaymentMethod();
    }

    /**
     * Get the configuration payment data
     *
     * @return array|null
     */
    public function getConfigPaymentData()
    {
        return $this->paymentConfig->getConfigPaymentData();
    }
}
