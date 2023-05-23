<?php

namespace shurjomukhi\shurjopay\Controller\Payment;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Mail\Template\TransportBuilderFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Shurjopay send email controller.
 *
 * @package shurjomukhi\shurjopay\Controller\Payment
 * @category Magento\Plugin
 * @module Shurjopay
 * @author     Md Wali Mosnad Ayshik
 * @since 2023/05/22
 */
class Sendemail extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;
    
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Sendemail constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        RequestInterface $request,
        TransportBuilder $transportBuilder,
        StoreManagerInterface $storeManager
    ) {
        $this->_request = $request;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Execute the send email action.
     */
    public function execute()
    {
        $this->successEmail();
    }

    /**
     * Send success email.
     */
    public function successEmail()
    {
        $paymentMethod = $this->_objectManager->create('shurjomukhi\shurjopay\Model\Shurjopay');
        $data = $paymentMethod->getCusMail();
        $storeIsActive = $this->_storeManager->getStore()->isActive();

        if ($storeIsActive == "1") {
            $store = $this->_storeManager->getStore()->getId();
            $templateVars = [
                'store_name' => $this->_storeManager->getStore()->getName(),
                'order_id' => $data['order_id'],
                'customer_name' => $data['cus_name'],
                'amount' => $data['total_amount'],
                'title' => $data['title'],
                'full_name' => $data['full_name'],
                'country' => $data['country'],
                'street' => $data['street'][0],
                'region' => $data['region'],
                'city' => $data['city'],
                'telephone' => $data['telephone']
            ];

            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('shurjopay_success_template')
                ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
                ->setTemplateVars($templateVars)
                ->setFrom('sales')
                ->addTo($data['cus_email'])
                ->getTransport();

            return $transport->sendMessage();
        }
    }

    /**
     * Send fail email.
     */
    public function failEmail()
    {
        $paymentMethod = $this->_objectManager->create('shurjomukhi\shurjopay\Model\Shurjopay');
        $data = $paymentMethod->getCusMail();
        $storeIsActive = $this->_storeManager->getStore()->isActive();

        if ($storeIsActive == "1") {
            $store = $this->_storeManager->getStore()->getId();
            $templateVars = [
                'store_name' => $this->_storeManager->getStore()->getName(),
                'order_id' => $data['order_id'],
                'customer_name' => $data['cus_name'],
                'amount' => $data['total_amount'],
                'title' => $data['title'],
                'full_name' => $data['full_name'],
                'country' => $data['country'],
                'street' => $data['street'][0],
                'region' => $data['region'],
                'city' => $data['city'],
                'telephone' => $data['telephone']
            ];

            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('shurjopay_fail_template')
                ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
                ->setTemplateVars($templateVars)
                ->setFrom('sales')
                ->addTo($data['cus_email'])
                ->getTransport();

            return $transport->sendMessage();
        }
    }

    /**
     * Send cancel email.
     */
    public function cancelEmail()
    {
        $paymentMethod = $this->_objectManager->create('shurjomukhi\shurjopay\Model\Shurjopay');
        $data = $paymentMethod->getCusMail();
        $storeIsActive = $this->_storeManager->getStore()->isActive();

        if ($storeIsActive == "1") {
            $store = $this->_storeManager->getStore()->getId();
            $templateVars = [
                'store_name' => $this->_storeManager->getStore()->getName(),
                'order_id' => $data['order_id'],
                'customer_name' => $data['cus_name'],
                'amount' => $data['total_amount'],
                'title' => $data['title'],
                'full_name' => $data['full_name'],
                'country' => $data['country'],
                'street' => $data['street'][0],
                'region' => $data['region'],
                'city' => $data['city'],
                'telephone' => $data['telephone']
            ];

            $transport = $this->_transportBuilder
                ->setTemplateIdentifier('shurjopay_cancel_template')
                ->setTemplateOptions(['area' => 'frontend', 'store' => $store])
                ->setTemplateVars($templateVars)
                ->setFrom('sales')
                ->addTo($data['cus_email'])
                ->getTransport();

            return $transport->sendMessage();
        }
    }
}
