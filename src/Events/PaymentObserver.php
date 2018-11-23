<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 23/11/2018
 * Time: 5:11 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Events;


use Xpressengine\Plugins\XeroCommerce\Models\PayLog;
use Xpressengine\Plugins\XeroCommerce\Models\Payment;

class PaymentObserver
{
    public function saved(Payment $payment)
    {
        $log = new PayLog();
        $log->status = $payment->is_paid;
        $log->pay_id = $payment->id;
        $log->ip = request()->ip();
        $log->url = request()->url();
        $log->save();
    }
}
