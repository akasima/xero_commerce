<?php
/**
 * Created by PhpStorm.
 * User: yeong-ung-gim
 * Date: 2018. 9. 12.
 * Time: 오후 5:37
 */

namespace Xpressengine\Plugins\XeroStore\Models;


use Illuminate\Database\Eloquent\Model;

class Order extends Model
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
            'Xpressengine\Plugins\XeroStore\Models\Option',
            'xero_store_option_order',
            'order_id',
            'option_id'
        );
    }

    public function payment()
    {
        return $this->belongsTo('Xpressengine\Plugins\XeroStore\Models\Payment', 'pay_id');
    }

    public function delivery()
    {
        return $this->belongsTo('Xpressengine\Plugins\XeroStore\Models\Delivery', 'delivery_id');
    }
}