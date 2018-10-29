<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 25/10/2018
 * Time: 11:19 AM
 */

namespace Xpressengine\XePlugin\XeroPay\Inicis;


use App\Facades\XeConfig;
use Xpressengine\Http\Request;
use Xpressengine\XePlugin\XeroPay\AbstractPaymentResponse;
use Xpressengine\XePlugin\XeroPay\Inicis\Libs\INIStdPayUtil;
use Xpressengine\XePlugin\XeroPay\PayCurl;

class InicisResponse extends AbstractPaymentResponse
{

    public function success()
    {
        return $this->request->get('resultCode')=='0000';
    }

    public function msg()
    {
        return $this->request->get('resultMsg');
    }

    public function getUniqueNo()
    {
        return $this->request->get('authToken');
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
