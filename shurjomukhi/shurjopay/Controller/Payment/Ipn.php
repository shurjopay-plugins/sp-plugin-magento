<?php

/**
 * Magento 2 plugin to handle IPN (Instant Payment Notification) for shurjoPay payments.
 *
 * @package shurjomukhi\shurjopay\Controller\Payment
 * @category Magento\Plugin
 * @module Shurjopay
 * @author Md Wali Mosnad Ayshik
 * @since 2023/05/22
 */

namespace shurjomukhi\shurjopay\Controller\Payment;

use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Action\Action;
use shurjomukhi\shurjopay\Model\Shurjopay;

class Ipn extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Ipn constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Execute IPN action
     *
     * @return void
     */
    public function execute()
    {
        /** @var Shurjopay $paymentMethod */
        $paymentMethod = $this->_objectManager->create(Shurjopay::class);
        if (!empty($this->getRequest()->getPostValue())) {
            $data = $this->getRequest()->getPostValue();
            $resp = $paymentMethod->ipnAction($data);
        } else {
            echo "<span align='center'><h2>IPN only accept POST request!</h2><p>Remember, We have set an IPN URL in first step so that your server can listen at the right moment when payment is done at Bank End. So, It is important to validate the transaction notification to maintain security and standard.As IPN URL already set in script. All the payment notification will reach through IPN prior to user return back. So it needs validation for amount and transaction properly.</p></span>";
        }
    }
}
