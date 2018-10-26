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
        return $this->request->get('LGD_RESPCODE') ==='0000';
    }

    public function msg()
    {
        return $this->request->get('LGD_RESPMSG');
    }

    public function getUniqueNo()
    {
        return $this->request->get('LGD_PAYKEY');
    }

    public function getDateTime()
    {
        return $this->request->get('LGD_PAYDATE');
    }

    public function getInfo()
    {
        return $this->request->all();
    }
}
