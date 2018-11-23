<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 26/10/2018
 * Time: 2:16 PM
 */

namespace Xpressengine\XePlugin\XeroPay\Inicis;


use Illuminate\Contracts\Support\Jsonable;
use Xpressengine\XePlugin\XeroPay\Models\Payment;
use Xpressengine\XePlugin\XeroPay\PaymentResponse;
use Xpressengine\XePlugin\XeroPay\PaymentResult;

class InicisResult extends PaymentResult
{
    private $arr;

    public function __construct($arr)
    {
        $this->arr = $arr;
    }

    public function success()
    {
        return $this->arr->resultCode == '0000';
    }

    public function msg()
    {
        return $this->arr->resultMsg;
    }

    public function getUniqueNo()
    {
        return $this->arr->tid;
    }

    public function getDateTime()
    {
        return $this->arr->applDate . $this->arr->applTime;
    }

    public function getInfo()
    {
        return $this->arr;
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

    public function fail()
    {
        return !$this->success();
    }

    public function getPayment()
    {
        return Payment::where('payable_unique_id',$this->arr->MOID)->first();
    }

    public function getReceipt()
    {
        if ($this->success()) {
            $info = ($this->getPayment()->is_paid_method) ?
                [
                    '결제승인번호' => $this->arr->applNum,
                    '결제금액' => number_format($this->arr->TotPrice) . '원'
                ] :
                [
                    '입금은행' => Inicis::$banks[$this->arr->VACT_BankCode] . '(' . $this->arr->VACT_Num . ')',
                    '예금주' => $this->arr->VACT_Name,
                    '입금기한' => $this->arr->VACT_Date
                ];
            if ($this->arr->payMethod == 'VCard' || $this->arr->payMethod == 'Card') {
                $info['카드번호'] = $this->arr->CARD_Num . '(' . Inicis::$cards[$this->arr->CARD_Code] . ')';
                $info['할부'] = ($this->arr->CARD_Interest == 1) ? $this->arr->CARD_Quota . '개월' : '일시불';
            }
        } else {
            $info = [
                '결제실패' => $this->msg()
            ];
        }
        return json_encode($info);
    }
}
