<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 26/10/2018
 * Time: 2:16 PM
 */

namespace Xpressengine\XePlugin\XeroPay\LG;

use Illuminate\Contracts\Support\Jsonable;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;

class LGResult implements PaymentResponse, Jsonable
{
    public $res;
    
    public function __construct( $arr)
    {
        $this->res = $arr;
    }

    public function success()
    {
        return $this->res->LGD_RESPCODE ==='0000';
    }

    public function msg()
    {
        return $this->res->LGD_RESPMSG;
    }

    public function getUniqueNo()
    {
        return $this->res->LGD_TID;
    }

    public function getDateTime()
    {
        return $this->res->LGD_PAYDATE;
    }

    public function getInfo()
    {
        return $this->res;
    }

    public function fail()
    {
        return !$this->success();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->getInfo());
    }
}
