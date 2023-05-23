<?php

/**
 * Magento 2 plugin to handle cancel action for shurjoPay payments.
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

class Cancel extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Cancel constructor.
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
     * Execute cancel action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {   
        // Handle the error action in the shurjoPay model
        $paymentMethod = $this->_objectManager->create('shurjomukhi\shurjopay\Model\Shurjopay');
        $paymentMethod->errorAction();
        
        // Send cancel email
        $mail = $this->_objectManager->create('shurjomukhi\shurjopay\Controller\Payment\Sendemail');

        // Allow sending cancel email only for remote addresses not in the whitelist
        $whitelist = array('127.0.0.1','::1');
        if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            $mail->CancelEmail();
        }

        // Redirect to the failure page in the checkout
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('checkout/onepage/failure', ['_secure' => true]);
    }
}
