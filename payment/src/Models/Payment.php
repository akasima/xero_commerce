<?php
namespace Xpressengine\XePlugin\XeroPay\Models;

class Payment extends \Xpressengine\Database\Eloquent\DynamicModel
{

    protected $guarded=[];
    const STATUS=[
        '생성',
        '요청',
        '시도',
        '취소'
    ];
    const CREATE=0;
    const REQ=1;
    const EXE=2;
    const CANCEL=3;
    protected $table='xero_pay_payment';
    public $incrementing = false;

    public function log()
    {
        return $this->hasMany(PayLog::class);
    }

    public function target()
    {
        return $this->morphTo('payable');
    }
}
