<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Wish extends DynamicModel
{
    protected $table = 'xero_commerce__wishes';
    protected $guarded=[];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * @return array
     */
    function getJsonFormat()
    {

    }
}
