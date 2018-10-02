<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Label extends DynamicModel
{
    protected $table = 'xero_commerce_label';

    public $timestamps = false;

    protected $fillable = ['name', 'eng_name'];
}
