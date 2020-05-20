<?php


namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItemCustomOptions\TextOption;
use Xpressengine\Plugins\XeroCommerce\Traits\CustomTableInheritanceTrait;

class OrderItemCustomOption extends Model
{
    use CustomTableInheritanceTrait;

    protected $table = 'xero_commerce__order_item_custom_options';

    protected $fillable = [
        'type',
        'name',
        'value',
        'display_value',
    ];

    public $timestamps = false;

    protected static $singleTableTypeField = 'type';

    protected static $singleTableSubclasses = [TextOption::class];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

}
