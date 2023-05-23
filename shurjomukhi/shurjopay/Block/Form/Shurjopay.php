<?php
/**
 * Magento 2 plugin to provide shurjoPay get way services.
 *
 * @package shurjomukhi\shurjopay\Block\Form
 * @category Magento\Plugin
 * @module Shurjopay
 * @author Md Wali Mosnad Ayshik
 * @since 2023/05/22
 */

namespace shurjomukhi\shurjopay\Block\Form;

use Magento\Payment\Block\Form;

/**
 * Class Shurjopay
 * @package shurjomukhi\shurjopay\Block\Form
 */
class Shurjopay extends Form
{

    /**
     * Instructions to display on the payment form
     *
     * @var string|null
     */
    protected $_instructions;

    /**
     * Get the payment instructions
     *
     * @return string|null
     */
    public function getInstructions()
    {
        if ($this->_instructions === null) {
            $method = $this->getMethod();
            $this->_instructions = $method->getConfigData('instructions');
        }
        return $this->_instructions;
    }
}
