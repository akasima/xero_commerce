<?php


namespace Xpressengine\XePlugin\XeroPay;


use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\Models\Payment;

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
    public function makePaymentRequest(Request $request, Payment $payment);

    /**
     * @param Request $request
     * @return PaymentResponse;
     */
    public function getResponse(Request $request);

    /**
     * @param Request $request
     * @param array $form
     * @return mixed
     */
    public function getResult(Request $request);
}
