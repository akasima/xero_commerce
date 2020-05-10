<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;
use Xpressengine\XePlugin\XeroPay\Payable;

class Order extends DynamicModel
{
    use Payable;
    protected $table = 'xero_commerce__orders';
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
        '임시', '결제대기', '상품준비', '배송중', '배송완료', '취소중', '취소완료', '교환중', '교환완료', '환불중', '환불완료'
    ];

    public function items()
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
            $this->code = self::TEMP;
        }

        return self::STATUS[$this->code];
    }

    public function userInfo()
    {
        return $this->belongsTo(UserInfo::class, 'user_id', 'user_id');
    }

    public function orderGroup()
    {
        return $this->hasManyThrough(OrderItemGroup::class, OrderItem::class);
    }

    function getPriceForPay()
    {
        $orderHandler = new OrderHandler();

        $summary = $orderHandler->getSummary($this->items);

        return $summary['sum'];
    }

    function getNameForPay()
    {
        $items = $this->items;
        $name = $items->first()->product->name . (($items->count() > 1) ? ' 외' . ($items->count() - 1) . '건' : '');
        $shortName = (mb_strlen($name) > 15) ? mb_substr($name, 0, 12) . '...' : $name;

        return $shortName;
    }

    function vBank($date, $info)
    {
        $payment = $this->payment;
        $payment->is_paid = 1;
        $payment->updated_at = $date;
        $payment->save();
        $orderHandler = new OrderHandler();
        $orderHandler->update($this);
    }

    function getIdforPay()
    {
        return $this->order_no;
    }
}
