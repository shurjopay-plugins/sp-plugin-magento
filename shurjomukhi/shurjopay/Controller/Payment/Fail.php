<?php

/**
 * Magento 2 plugin to handle fail action for shurjoPay payments.
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
use shurjomukhi\shurjopay\Controller\Payment\Sendemail;
use shurjomukhi\shurjopay\Model\Shurjopay;

class Fail extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Fail constructor.
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
     * Execute fail action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var Shurjopay $paymentMethod */
        $paymentMethod = $this->_objectManager->create(Shurjopay::class);
        $paymentMethod->errorAction();

        /** @var Sendemail $mail */
        $mail = $this->_objectManager->create(Sendemail::class);

        $whitelist = array('127.0.0.1', '::1');
        if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            $mail->FailEmail();
        }

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('checkout/onepage/failure', ['_secure' => true]);
    }
}
