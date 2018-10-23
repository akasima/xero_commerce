<?php


namespace Xpressengine\Plugins\XeroCommerce\Services;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Xpressengine\Plugins\XeroCommerce\Models\Agreement;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderAgreement;
use Xpressengine\Plugins\XeroCommerce\Models\UserAgreement;

class AgreementService
{
    static function check($type)
    {
        return UserAgreement::where('user_id', Auth::id())
            ->whereHas('agreement', function ($query) use ($type) {
                $query
                    ->where('type', $type)
                    ->latest('version');
            })
            ->exists();
    }

    static function userAgree($agree_id)
    {
        $user_agreement = new UserAgreement();
        $user_agreement->user_id = Auth::id();
        $user_agreement->agreement_id = $agree_id;
        $user_agreement->save();
    }

    static function orderAgree(Order $order, $agree_id)
    {
        OrderAgreement::updateOrCreate(
            [
                'order_id' => $order->id,
                'agreement_id' => $agree_id
            ],
            [
                'updated_at' => now()
            ]
        );
    }

    static function get($type)
    {
        return Agreement::where('type', $type)
            ->latest('version')
            ->first();
    }
}
