<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 26/10/2018
 * Time: 2:16 PM
 */

namespace Xpressengine\XePlugin\XeroPay\LG;

use Illuminate\Contracts\Support\Jsonable;
use Xpressengine\XePlugin\XeroPay\Models\Payment;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;
use Xpressengine\XePlugin\XeroPay\PaymentResult;

class LGResult extends PaymentResult
{
    public $res;

    public function __construct($arr)
    {
        $this->res = $arr;
    }

    public function success()
    {
        return $this->res->LGD_RESPCODE === '0000';
    }

    public function msg()
    {
        return $this->res->LGD_RESPMSG;
    }

    public function getUniqueNo()
    {
        return $this->res->LGD_RESPONSE[0]->LGD_TID;
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

    public function getPayment()
    {
        return Payment::where('payable_unique_id',$this->res['LGD_OID'])->first();
    }

    public function getReceipt()
    {
        if ($this->success()) {
            $res = $this->res->LGD_RESPONSE[0];
            $info =
                [
                    '결제금액' => number_format($res->LGD_AMOUNT) . '원',
                    '결제기관' => $res->LGD_FINANCENAME
                ];
            if ($res->LGD_PAYTYPE == 'SC0040') {
                $info['입금계좌'] = $res->LGD_ACCOUNTNUM;
            }
            if ($res->LGD_PAYTYPE == 'SC0010') {
                $info['카드번호'] = $res->LGD_CARDNUM;
                $info['할부'] = ($res->LGD_CARDINSTALLMONTH == 0) ? '일시불' : $res->LGD_CARDINSTALLMONTH . '개월';
            }
        } else {
            $info = [
                '결제실패' => $this->msg()
            ];
        }
        return json_encode($info);
    }
}
