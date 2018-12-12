<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class OrderDelivery extends DynamicModel
{
    protected $table = 'xero_commerce_order_delivery';

    const READY = 0;
    const PROCESSING = 1;
    const DONE = 2;
    const BACK = 3;

    const STATUS = [
        '준비중', '배송중', '완료', '반송'
    ];

    public function company()
    {
        return $this->belongsTo(DeliveryCompany::class);
    }

    public function setShipNo($ship_no)
    {
        $this->ship_no = $ship_no;
        $this->status = self::PROCESSING;
        $this->save();

        return $this;
    }

    public function complete()
    {
        $this->status = self::DONE;
        $this->save();

        return $this;
    }

    public function getStatus()
    {
        return self::STATUS[$this->status];
    }

    public function getUrl()
    {
        return $this->company->uri . $this->ship_no;
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
