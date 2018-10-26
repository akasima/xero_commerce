<?php


namespace Xpressengine\XePlugin\XeroPay;


use Illuminate\Contracts\Support\Jsonable;
use Xpressengine\Http\Request;

abstract class AbstractPaymentRequest implements PaymentRequest, Jsonable
{

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
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
