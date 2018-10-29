<?php
namespace Xpressengine\XePlugin\XeroPay\Models;

class Payment extends \Xpressengine\Database\Eloquent\DynamicModel
{
    protected $table='xero_pay_payment';
    public $incrementing = false;

    public function log()
    {
        return $this->hasMany(PayLog::class);
    }
}
