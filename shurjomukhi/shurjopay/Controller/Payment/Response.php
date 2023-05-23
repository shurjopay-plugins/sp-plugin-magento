<?php
/**
 * Shurjopay response controller.
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

class Response extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Response constructor.
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
     * Execute Shurjopay response action.
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        /** @var Shurjopay $paymentMethod */
        $paymentMethod = $this->_objectManager->create(Shurjopay::class);

        $orderId = trim($_REQUEST['order_id']);
        $verification = $paymentMethod->verification($orderId);

        if ($verification == "success") {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('shurjopay/payment/success', ['_secure' => true]);
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $resultRedirect->setPath('shurjopay/payment/fail', ['_secure' => true]);
        }
    }
}
