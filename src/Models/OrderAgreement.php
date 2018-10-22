<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class OrderAgreement extends DynamicModel
{
    protected $table = 'xero_commerce_order_agreement';
    protected $guarded=[];

    public function agreement()
    {
        return $this->belongsTo(Agreement::class);
    }

}
