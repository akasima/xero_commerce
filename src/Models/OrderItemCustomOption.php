<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItemCustomOption extends Model
{
    protected $table = 'xero_commerce__order_item_custom_options';

    protected $fillable = [
        'type',
        'name',
        'value',
        'display_value',
    ];

    public $timestamps = false;

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

}
