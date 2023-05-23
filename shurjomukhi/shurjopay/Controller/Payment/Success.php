<?php

namespace shurjomukhi\shurjopay\Controller\Payment;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use shurjomukhi\shurjopay\Controller\Payment\Sendemail;
use Magento\Framework\Controller\ResultFactory;

/**
 * Shurjopay success controller.
 *
 * @package shurjomukhi\shurjopay\Controller\Payment
 * @category Magento\Plugin
 * @module Shurjopay
 * @author Md Wali Mosnad Ayshik
 * @since 2023/05/22
 */
class Success extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @var \shurjomukhi\shurjopay\Controller\Payment\Sendemail
     */
    protected $sendEmailController;

    /**
     * Success constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \shurjomukhi\shurjopay\Controller\Payment\Sendemail $sendEmailController
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Sendemail $sendEmailController
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->sendEmailController = $sendEmailController;
        parent::__construct($context);
    }

    /**
     * Execute the success action.
     */
    public function execute()
    {
        $whitelist = ['127.0.0.1', '::1'];
        if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            $this->sendEmailController->successEmail();
        }

        return $this->resultPageFactory->create();
    }
}
