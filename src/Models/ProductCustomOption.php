<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\Model;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions\SelectOption;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOptions\TextOption;
use Xpressengine\Plugins\XeroCommerce\Traits\CustomTableInheritanceTrait;

class ProductCustomOption extends Model
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
        'value'
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'settings' => 'json',
    ];

    protected $appends = [
        'value',
        'display_value',
    ];

    protected static $singleTableTypeField = 'type';

    protected static $singleTableSubclasses = [TextOption::class, SelectOption::class];

    protected $defaultValue = '';

    public function renderValueInput(array $attrs)
    {
        // 상속받아 구현 할것
        return '';
    }

    /**
     * 기본값을 지정
     * @return string
     */
    public function getValueAttribute()
    {
        return $this->defaultValue;
    }

    /**
     * 기본값을 지정
     */
    public function setValueAttribute($value)
    {
        $this->defaultValue = $value;
    }

    /**
     * 보여지는 기본값 지정
     * @return string
     */
    public function getDisplayValueAttribute()
    {
        return $this->defaultValue;
    }

}
