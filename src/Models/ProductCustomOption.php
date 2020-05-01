<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions\DateOption;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions\SelectOption;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions\TextAreaOption;
use Xpressengine\Plugins\XeroCommerce\Traits\CustomTableInheritanceTrait;

class ProductCustomOption extends DynamicModel
{
    use CustomTableInheritanceTrait;

    protected $table = 'xero_commerce__product_custom_options';

    protected $fillable = [
        'product_id',
        'name',
        'description',
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

    protected static $singleTableSubclasses = [TextAreaOption::class, DateOption::class, SelectOption::class];

    public static $singleTableType = 'text';

    public static $singleTableName = '텍스트';

    public function renderHtml(array $attrs)
    {
        $result = '<input type="text" ';
        foreach ($attrs as $key => $value) {
            $result .= "$key=\"$value\" ";
        }
        if($this->is_required) {
            $result .= 'required ';
        }
        $result .= '/>';
        return $result;
    }

}
