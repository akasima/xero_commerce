<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;

class Badge extends DynamicModel
{
    protected $table = 'xero_commerce_badge';

    public $timestamps = false;

    protected $fillable = ['name', 'eng_name', 'background_color', 'text_color'];
}
