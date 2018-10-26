<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 26/10/2018
 * Time: 9:40 AM
 */

namespace Xpressengine\XePlugin\XeroPay\LG;


use Xpressengine\XePlugin\XeroPay\AbstractPaymentResponse;

class LGResponse extends AbstractPaymentResponse
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
