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
     * @return PaymentResult
     */
    public function getResult(Request $request);

    public function vBank(Request $request);

    /**
     * @param Payment $payment
     * @param $reason
     * @return PaymentResponse
     */
    public function cancel(Payment $payment, $reason);
}
