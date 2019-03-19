<?php
namespace Xpressengine\XePlugin\XeroPay\Models;

class PayLog extends \Xpressengine\Database\Eloquent\DynamicModel
{
    protected $table = 'xero_pay_log';

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
