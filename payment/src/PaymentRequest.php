<?php


namespace Xpressengine\XePlugin\XeroPay;


use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\Models\Payment;

interface PaymentRequest
{
    public function __construct(Request $request, Payment $payment);

    public function getMethod();

    public function getPrice();

    public function validate();

    public function encrypt();

    public function setForm();
}
