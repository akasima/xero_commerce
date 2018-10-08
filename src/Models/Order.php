<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;

class Order extends DynamicModel
{
    protected $table = 'xero_commerce_order';
    public $incrementing = false;

    const TEMP = 0;
    const ORDERED = 1;
    const PAID = 2;
    const DELIVER = 3;
    const COMPLETE = 4;
    const CANCELING = 5;
    const CANCELED = 6;
    const EXCHANGING = 7;
    const EXCHANGED = 8;
    const REFUNDING = 9;
    const REFUNDED = 10;

    const STATUS = [
        '임시', '결제대기', '상품준비', '배송중', '배송완료','취소중', '취소완료', '교환중', '교환완료', '환불중', '환불완료'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne('Xpressengine\Plugins\XeroCommerce\Models\Payment');
    }

    public function getStatus()
    {
        if (is_null($this->code)) {
            $this->code = 0;
        }
        return self::STATUS[$this->code];
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }
}
