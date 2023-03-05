<?php


namespace shurjopay\magento\Model\Ui;

use shurjopay\magento\Gateway\Http\Client\ClientMock;

class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
 
    const CODE = 'customPaymentGateway';

    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'transactionResults' => [
                        ClientMock::SUCCESS => __('Success'),
                        ClientMock::FAILURE => __('Fraud')
                    ]
                ]
            ]
        ];
    }
}
