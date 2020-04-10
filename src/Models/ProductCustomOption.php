<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions\DateOption;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions\TextAreaOption;
use Xpressengine\Plugins\XeroCommerce\Traits\CustomTableInheritanceTrait;

class ProductCustomOption extends DynamicModel
{
    use CustomTableInheritanceTrait;

    protected $table = 'xero_commerce_product_custom_option';

    protected $fillable = [
        'product_id',
        'name',
        'type',
        'sort_order',
        'is_required',
        'settings',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'settings' => 'json',
    ];

    protected static $singleTableTypeField = 'type';

    protected static $singleTableSubclasses = [TextAreaOption::class, DateOption::class];

    public static $singleTableType = 'text';

    public static $singleTableName = '텍스트';

}
