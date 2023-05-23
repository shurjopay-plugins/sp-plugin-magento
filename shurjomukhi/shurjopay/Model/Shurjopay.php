<?php
/**
 * Magento 2 plugin to provide shurjoPay gateway services.
 *
 * @package shurjomukhi\shurjopay\Model
 * @category Magento\Plugin
 * @module Shurjopay
 * @author Md Wali Mosnad Ayshik
 * @since 2023/05/22
 */
/**
 * Shurjopay payment method model
 */
namespace shurjomukhi\shurjopay\Model;

use Magento\Quote\Api\Data\CartInterface;
use Magento\Payment\Model\Method\AbstractMethod;
use shurjomukhi\shurjopay\Model\Config\Source\Order\Status\Paymentreview;
use Magento\Sales\Model\Order;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\ObjectManager;

/**
 * Pay In Store payment method model
 */
class Shurjopay extends AbstractMethod
{
    /**
     * @var bool
     */
    protected $_isInitializeNeeded = true;
    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = 'shurjopay';
    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = true;
    /**
     * Payment additional info block
     *
     * @var string
     */
    protected $_formBlockType = 'shurjomukhi\shurjopay\Block\Form\Shurjopay';
    /**
     * Sidebar payment info block
     *
     * @var string
     */
    protected $_infoBlockType = 'Magento\Payment\Block\Info\Instructions';
    protected $_gateUrl = "https://engine.shurjopayment.com/";
    protected $_testUrl = "https://sandbox.shurjopayment.com/";
    protected $_test;
    protected $orderFactory;
    /**
     * Get payment instructions text from config
     *
     * @return string
     */
    public function getInstructions()
    {
        return trim($this->getConfigData('instructions'));
    }
    /**
     * Constructor
     *
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory
     * @param \Magento\Payment\Helper\Data $paymentData
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Payment\Model\Method\Logger $logger
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(\Magento\Framework\Model\Context $context, \Magento\Framework\Registry $registry, \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory, \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory, \Magento\Payment\Helper\Data $paymentData, \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig, \Magento\Payment\Model\Method\Logger $logger, \Magento\Framework\Module\ModuleListInterface $moduleList, \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate, \Magento\Sales\Model\OrderFactory $orderFactory, \Magento\Framework\App\RequestInterface $request, \Magento\Checkout\Model\Session $checkoutSession, \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null, \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null, array $data = [])
    {
        $this->orderFactory = $orderFactory;
        $this->_request = $request;
        $this->_checkoutSession = $checkoutSession;
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $paymentData, $scopeConfig, $logger, $resource, $resourceCollection, $data);
        if ($this->getConfigData('test')) {
            $this->domainName = $this->_testUrl;
        } else {
            $this->domainName = $this->_gateUrl;
        }
        $this->token_url = $this->domainName . "api/get_token";
        $this->payment_url = $this->domainName . "api/secret-pay";
        $this->verification_url = $this->domainName . "api/verification/";
    }
    /**
     * Get token from shurjoPay API
     *
     * @return string|null
     */
    public function getToken()
    {
        $postFields = array('username' => $this->getConfigData('merchant_id'), 'password' => $this->getConfigData('pass_word_1'), );
        if (empty($this->token_url) || empty($postFields)) {
            return null;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->token_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        if ($response === false) {
            echo json_encode(curl_error($ch));
        }
        curl_close($ch);
        return $response;
    }
    /**
     * Get the amount for the given order ID
     *
     * @param string $orderId
     * @return float
     */
    public function getAmount($orderId)
    {
        $orderFactory = $this->orderFactory;
        $order = $orderFactory->create()->loadByIncrementId($orderId);
        return $order->getGrandTotal();
    }
    /**
     * Get the order object for the given order ID
     *
     * @param string $orderId
     * @return \Magento\Sales\Model\Order
     */
    protected function getOrder($orderId)
    {
        $orderFactory = $this->orderFactory;
        return $orderFactory->create()->loadByIncrementId($orderId);
    }
    /**
     * Initialize payment action and update state object
     *
     * @param string $paymentAction
     * @param \Magento\Sales\Model\Order\State $stateObject
     * @return void
     */
    public function initialize($paymentAction, $stateObject)
    {
        $state = $this->getConfigData('order_status');
        $this->_gateUrl = $this->getConfigData('cgi_url');
        $this->_testUrl = $this->getConfigData('cgi_url_test_mode');
        $this->_test = $this->getConfigData('test');
        $stateObject->setState($state);
        $stateObject->setStatus($state);
        $stateObject->setIsNotified(false);
    }
    /**
     * Check if the payment method is available
     *
     * @param \Magento\Quote\Api\Data\CartInterface $quote
     * @return bool
     */
    public function isAvailable(\Magento\Quote\Api\Data\CartInterface $quote = null)
    {
        if ($quote === null) {
            return false;
        }
        return parent::isAvailable($quote) && $this->isCarrierAllowed($quote->getShippingAddress()->getShippingMethod());
    }
    /**
     * Get the gateway URL based on test mode
     *
     * @return string
     */
    public function getGateUrl()
    {
        if ($this->getConfigData('test')) {
            return $this->_testUrl;
        } else {
            return $this->_gateUrl;
        }
    }
    /**
     * Check if the carrier is allowed for the payment method
     *
     * @param string $shippingMethod
     * @return bool
     */
    protected function isCarrierAllowed($shippingMethod)
    {
        $logger = ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class);
        if (empty($shippingMethod)) {
            $shippingMethod = "No";
        }
        $check = @strpos($this->getConfigData('allowed_carrier'), $shippingMethod) !== true;
        $logger->debug('Check value: ' . var_export($check, true));
        return $check;
    }
    /**
     * Generate the hash value for shurjoPay API
     *
     * @param string $login
     * @param float $sum
     * @param string $pass
     * @param int|null $id
     * @return string
     */
    public function generateHash($login, $sum, $pass, $id = null)
    {
        $hashData = array("MrchLogin" => $login, "OutSum" => $sum, "InvId" => $id, "currency" => "BDT", "pass" => $pass, );
        $hash = strtoupper(md5(implode(":", $hashData)));
        return $hash;
    }
    /**
     * Process the IPN (Instant Payment Notification) action
     *
     * @param mixed $response
     * @return void
     */
    public function ipnAction($response)
    {
        // Process the IPN response from shurjoPay

    }
    /**
     *This function retrieves post data for a given order ID and prepares it for payment processing.
     *@param string $orderId The ID of the order.
     *@return string The checkout URL for the payment.
     */
    public function getPostData($orderId)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId); // Use increment id here.
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        try {
            $token = json_decode($this->getToken(), true);
            if ($token === null) {
                throw new Exception('Failed to decode token JSON: ' . json_last_error_msg());
            }
            $body = [
                // Store information
                'token' => $token['token'],
                'store_id' => $token['store_id'],
                'prefix' => $this->getConfigData('merchant_prefix'),
                'currency' => $storeManager->getStore()->getCurrentCurrency()->getCode(),
                'return_url' => $storeManager->getStore()->getBaseUrl() . 'shurjopay/payment/response',
                'cancel_url' => $storeManager->getStore()->getBaseUrl() . 'shurjopay/payment/response',
                'amount' => round($this->getAmount($orderId), 2),
                // Order information
                'order_id' => $orderId,
                'discsount_amount' => 0,
                'disc_percent' => 0,
                // Customer information
                'client_ip' => $_SERVER['REMOTE_ADDR'],
                'customer_name' => $order->getCustomerName(),
                'customer_phone' => $order->getBillingAddress()->getTelephone(),
                'customer_email' => $order->getCustomerEmail(),
                'customer_address' => $order->getBillingAddress()->getStreet()[0],
                'customer_city' => $order->getBillingAddress()->getCity(),
                'customer_state' => $order->getBillingAddress()->getRegionId(),
                'customer_postcode' => $order->getBillingAddress()->getPostcode(),
                'customer_country' => $order->getBillingAddress()->getCountryId(),
                'value1' => $orderId,
            ];
            $headers = ['Content-Type:application/json', 'Authorization: Bearer ' . $token['token'],];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->payment_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            $response = curl_exec($ch);
            if ($response === false) {
                throw new Exception('curl_exec error: ' . curl_error($ch));
            }
            $urlData = json_decode($response);
            if ($urlData === null) {
                throw new Exception('Failed to decode response JSON: ' . json_last_error_msg());
            }
            curl_close($ch);
            return $urlData->checkout_url;
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    /**
     *This function verifies the payment for a given order ID.
     *@param string $order_id The ID of the order.
     *@return string The result of the verification: "success" or "failed".
     */
    public function verification($order_id)
    {
        $token = json_decode($this->getToken(), true);
        $header = array('Content-Type:application/json', 'Authorization: Bearer ' . $token['token']);
        $postFields = json_encode(array('order_id' => $order_id));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->verification_url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/0 (Windows; U; Windows NT 0; zh-CN; rv:3)");
        $response = curl_exec($ch);
        if ($response === false) {
            echo json_encode(curl_error($ch));
        }
        curl_close($ch);
        $response = json_decode($response);
        $orderId = $response[0]->value1;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);
        if ($response[0]->sp_code == '1000') {
            $order->setState(Order::STATE_PROCESSING)->setStatus(Order::STATE_PROCESSING)->addStatusToHistory(Order::STATE_PROCESSING, 'Payment Validated by shurjoPay');
            $order->save();
            return "success";
        } else {
            return "failed";
        }
    }
    /**
     *This function retrieves the payment method for the current order.
     *@return string The payment method title.
     */
    public function getPaymentMethod()
    {
        $orderId = $this->_checkoutSession->getLastRealOrderId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);
        $payment = $order->getPayment();
        $method = $payment->getMethodInstance();
        $methodTitle = $method->getTitle();
        return $methodTitle;
    }
    /**
     *This function retrieves the configuration payment data.
     *@return string The payment data configuration.
     */
    public function getConfigPaymentData()
    {
        return $this->getConfigData('title');
    }
    /**
     *This function retrieves customer email and address data for a given order ID.
     *@return array The customer email and address data.
     */
    public function getCusMail()
    {
        $orderId = $this->_checkoutSession->getLastRealOrderId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);
        $PostData['order_id'] = $orderId;
        $PostData['cus_email'] = $order->getCustomerEmail();
        $PostData['url'] = $this->getConfigData('test');
        $PostData['total_amount'] = round($this->getAmount($orderId), 2);
        $PostData['cus_name'] = $order->getCustomerName();
        $PostData['cus_phone'] = $order->getBillingAddress()->getTelephone();
        $PostData['title'] = $this->getConfigData('title');
        $PostData['full_name'] = $order->getBillingAddress()->getFirstname() . " " . $order->getBillingAddress()->getLastname();
        $PostData['country'] = $order->getBillingAddress()->getCountryId();
        $PostData['street'] = $order->getBillingAddress()->getStreet();
        $PostData['region'] = $order->getBillingAddress()->getRegionId();
        $PostData['city'] = $order->getBillingAddress()->getCity() . ", " . $order->getBillingAddress()->getPostcode();
        $PostData['telephone'] = $order->getBillingAddress()->getTelephone();
        return $PostData;
    }
    /**
     *This function handles the error action for a declined payment.
     */
    public function errorAction()
    {
        $orderId = $this->_checkoutSession->getLastRealOrderId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $orderState = Order::STATE_CANCELED;
        $order->setState($orderState, true, 'Gateway has declined the payment.')->setStatus($orderState);
        $order->save();
    }
    /**
     *This function retrieves the success message data for a successful payment.
     *@return array The success message data.
     */
    public function getSuccessMsg()
    {
        $orderId = $this->_checkoutSession->getLastRealOrderId();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Model\Order')->loadByIncrementId($orderId);
        $_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeManager = $_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $PostData = [];
        $PostData['cus_name'] = $order->getCustomerName();
        $PostData['cus_email'] = $order->getCustomerEmail();
        // $PostData['cus_phone'] = $order->getBillingAddress()->getTelephone();
        $PostData['total_amount'] = round($this->getAmount($orderId), 2);
        $PostData['tran_id'] = $orderId;
        $PostData['state'] = $order->getState();
        return $PostData;
    }
}