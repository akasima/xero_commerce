<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOption;

class ProductOptionSettingService
{
    /** @var ProductOptionHandler $productOptionHandler */
    protected $productOptionHandler;

    public function __construct()
    {
        $this->productOptionHandler = app('xero_commerce.productOptionHandler');
    }

    /**
     * @param Request $request
     * @param $productId
     */
    public function saveOptions(Request $request, $productId)
    {
        // 기존 옵션들은 삭제
        $this->removeProductOptions($productId);
        // 새로운 옵션들을 입력
        $optionsData = $request->get('options');
        // values가 있는 경우에만 저장
        if($this->checkOptionValues($optionsData)) {
            foreach ($optionsData as $optionData) {
                $optionData['product_id'] = $productId;
                $this->productOptionHandler->store($optionData);
            }
}
    }

    public function removeProductOptions($productId)
    {
        $items = ProductOption::where('product_id', $productId)->get();

        foreach ($items as $item) {
            $this->productOptionHandler->destroy($item);
        }
    }

    public function checkOptionValues($options)
    {
        // 하나라도 values에 값이 들어있다면 통과
        foreach ($options as $i => $option) {
            if(!empty($option['values'])) return true;
        }
        return false;
    }
}
