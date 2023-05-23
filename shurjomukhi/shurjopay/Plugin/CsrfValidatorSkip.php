<?php
/**
 * Magento 2 plugin to provide shurjoPay gateway services.
 *
 * @package shurjomukhi\shurjopay\Plugin
 * @category Magento\Plugin
 * @module Shurjopay
 * @author Md Wali Mosnad Ayshik
 * @since 2023/05/22
 */

namespace shurjomukhi\shurjopay\Plugin;

class CsrfValidatorSkip
{
    /**
     * Skip CSRF validation for the "shurjopay" module.
     *
     * @param object $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\App\ActionInterface $action
     * @return void
     */
    public function aroundValidate(
        $subject,
        \Closure $proceed,
        $request,
        $action
    ) {
        if ($request->getModuleName() === 'shurjopay') {
            return;
        }
        $proceed($request, $action);
    }
}
