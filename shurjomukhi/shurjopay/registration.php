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

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'shurjomukhi_shurjopay',
    __DIR__
);