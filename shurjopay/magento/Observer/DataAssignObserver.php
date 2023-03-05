<?php

namespace shurjopay\magento\Observer;


use Magento\Framework\Event\Observer;

class DataAssignObserver extends \Magento\Payment\Observer\AbstractDataAssignObserver
{

    public function execute(Observer $observer)
    {
        $method = $this->readMethodArgument($observer);
        $data = $this->readDataArgument($observer);

        $paymentInfo = $method->getInfoInstance();

        if ($data->getDataByKey('transaction_result') !== null) {
            $paymentInfo->setAdditionalInformation(
                'transaction_result',
                $data->getDataByKey('transaction_result')
            );
        }
    }
}
