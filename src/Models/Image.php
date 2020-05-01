<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Image extends DynamicModel
{
    protected $table = 'xero_commerce__images';

    public $timestamps = false;
}
