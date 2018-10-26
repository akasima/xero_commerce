<?php


namespace Xpressengine\XePlugin\XeroPay;


use Xpressengine\Http\Request;

interface PaymentHandler
{
    /**
     * @return array
     */
    public function getMethodList();

    public function prepare();

    /**
     * @param Request $request
     * @return PaymentRequest
     */
    public function makePaymentRequest(Request $request);

    /**
     * @param Request $request
     * @return PaymentResponse
     */
    public function execute(Request $request);
}
