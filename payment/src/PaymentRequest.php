<?php


namespace Xpressengine\XePlugin\XeroPay;


use Xpressengine\Http\Request;

interface PaymentRequest
{
    public function __construct(Request $request);

    public function getMethod();

    public function getPrice();

    public function validate();

    public function encrypt();

    public function setForm();

    public function url();
}