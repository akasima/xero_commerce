<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Carbon\Carbon;
use Xpressengine\Database\Eloquent\DynamicModel;

/**
 * 주문의 배송정보
 * Class OrderShipment
 * @package Xpressengine\Plugins\XeroCommerce\Models
 */
class OrderShipment extends DynamicModel
{
    protected $table = 'xero_commerce__order_shipments';

    const READY = 0;
    const PROCESSING = 1;
    const DONE = 2;
    const BACK = 3;

    const STATUS = [
        '준비중', '배송중', '완료', '반송'
    ];

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
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
        $this->completed_at = new Carbon();
        $this->save();

        return $this;
    }

    public function getStatus()
    {
        return self::STATUS[$this->status];
    }

    public function getUrl()
    {
        return $this->carrier->uri . $this->ship_no;
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
