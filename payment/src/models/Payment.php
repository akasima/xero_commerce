<?php
namespace Xpressengine\XePlugin\XeroPay\Models;

class Payment extends \Xpressengine\Database\Eloquent\DynamicModel
{

    const STATUS=[
        '생성',
        '요청',
        '시도'
    ];
    const CREATE=0;
    const REQ=1;
    const EXE=2;
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
