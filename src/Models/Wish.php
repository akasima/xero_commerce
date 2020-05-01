<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Wish extends DynamicModel
{
    protected $table = 'xero_commerce__wishes';
    protected $guarded=[];

    public function sellType()
    {
        return $this->morphTo('type');
    }

    /**
     * @return array
     */
    function getJsonFormat()
    {

    }
}
