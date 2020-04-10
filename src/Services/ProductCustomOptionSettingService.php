<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductCustomOptionHandler;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCustomOption;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOption;

class ProductCustomOptionSettingService
{
    /** @var ProductCustomOptionHandler $productCustomOptionHandler */
    protected $productCustomOptionHandler;

    public function __construct()
    {
        $this->productCustomOptionHandler = app('xero_commerce.productCustomOptionHandler');
    }

    /**
     * @param Request $request
     * @param $productId
     */
    public function saveOptions(Request $request, $productId)
    {
        // 새로운 옵션들을 입력
        $optionsData = $request->get('custom_options');
        $savedIds = [];
        if($optionsData) {
            foreach ($optionsData as $optionData) {
                $optionData['product_id'] = $productId;

                if(empty($optionId = array_get($optionData, 'id'))) {
                    $savedOption = $this->productCustomOptionHandler->store($optionData);
                } else {
                    $option = ProductCustomOption::find($optionId);
                    $savedOption = $this->productCustomOptionHandler->update($option, $optionData);
                }
                $savedIds[] = $savedOption->id;
            }
        }
        $this->removeObsoleteOptions($savedIds);
    }

    /**
     * 버려진 ID들 삭제
     * @param array $savedIds
     */
    public function removeObsoleteOptions(array $savedIds)
    {
        $obsoletes = ProductCustomOption::whereNotIn('id', $savedIds)->get();

        foreach ($obsoletes as $option) {
            $this->productCustomOptionHandler->destroy($option);
        }
    }

}
