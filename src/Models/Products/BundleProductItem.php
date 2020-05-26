<?php

namespace Xpressengine\Plugins\XeroCommerce\Models\Products;

use Xpressengine\Plugins\XeroCommerce\Models\OrderableItem;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOption;

class BundleProductItem extends OrderableItem
{
    protected $table = 'xero_commerce__bundle_product_items';

    protected $fillable = [
        'product_id',
        'product_variant_id',
        'custom_options',
        'count',
    ];

    protected $casts = [
        'custom_options' => 'collection'
    ];

    public function getCustomOptions()
    {
        $customOptions = $this->custom_options;
        $typeMap = ProductCustomOption::getSingleTableTypeMap();

        return collect($customOptions)->map(function($option) use ($typeMap) {
            $type = $option['type'];
            $class = $typeMap[$type];
            $option['input_html'] = (new $class)->renderInputHtml();

            $option['display_value'] = isset($option['display_value']) ? $option['display_value'] : $option['value'];
            return $option;
        });
    }

    public function setCustomOptions(array $customOptions)
    {
        $this->custom_options = $customOptions;
    }

    public function getShippingFeeType()
    {
        // 번들하위품목은 상위품목에서 선불/착불을 결정한다
        return 0;
    }

    function toArray()
    {
        return array_merge(parent::toArray(), [
            // 커스텀옵션 가져올때 display_value 삽입을 위해 오버라이딩
            'custom_options' => $this->getCustomOptions()
        ]);
    }


}
