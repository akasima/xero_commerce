<?php


namespace Xpressengine\XePlugin\XeroPay;


use Illuminate\Contracts\Support\Jsonable;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\Models\Payment;

abstract class AbstractPaymentRequest implements PaymentRequest, Jsonable
{

    protected $request;
    protected $payment;

    public function __construct(Request $request, Payment $payment)
    {
        $this->request = $request;
        $this->payment = $payment;
    }

    public function getPrice()
    {
        $this->payment->price;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return \GuzzleHttp\json_encode($this->setForm());
    }

    public function validate()
    {
        return true;
    }

    public function getRequest($key)
    {
        return $this->request->get($key);
    }
}
