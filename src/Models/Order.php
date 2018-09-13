<?php

namespace Xpressengine\Plugins\XeroStore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroStore\Handlers\OrderHandler;

class Order extends Model implements \Xpressengine\Plugins\XeroStore\Order
{
    protected $table = 'xero_store_order';

    public function products()
    {
        return $this->belongsToMany(
            'Xpressengine\Plugins\XeroStore\Models\Product',
            'xero_store_option_order',
            'order_id',
            'product_id'
        );
    }

    public function options()
    {
        return $this->belongsToMany(
            'Xpressengine\Plugins\XeroStore\Models\ProductOptionItem',
            'xero_store_option_order',
            'order_id',
            'option_id'
        )->withPivot(['product_id', 'count', 'delivery_id'])->withTimestamps();
    }

    public function payment()
    {
        return $this->hasOne('Xpressengine\Plugins\XeroStore\Models\Payment');
    }

    public function getStatus()
    {
        if (is_null($this->code)) {
            $this->code = 0;
        }
        return OrderHandler::STATUS[$this->code];
    }

    public function readyToOrder()
    {
        $this->code = self::TEMP;
        $this->user_id = Auth::id();
        $this->save();
    }
}
