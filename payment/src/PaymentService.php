<?php


namespace Xpressengine\XePlugin\XeroPay;


use Xpressengine\Http\Request;

class PaymentService
{
    protected $handler;

    public function __construct($payment_id)
    {
        $this->handler = app($payment_id);
    }

    public function formatRequest(Request $request)
    {

    }

    public function execute(PaymentRequest $paymentRequest)
    {

    }
}