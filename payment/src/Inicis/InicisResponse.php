<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 25/10/2018
 * Time: 11:19 AM
 */

namespace Xpressengine\XePlugin\XeroPay\Inicis;


use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\AbstractPaymentResponse;

class InicisResponse extends AbstractPaymentResponse
{

    public function success()
    {
        // TODO: Implement success() method.
    }

    public function msg()
    {
        // TODO: Implement msg() method.
    }

    public function getUniqueNo()
    {
        // TODO: Implement getUniqueNo() method.
    }

    public function getDateTime()
    {
        // TODO: Implement getDateTime() method.
    }

    public function getInfo()
    {
        return $this->request->all();
    }
}
