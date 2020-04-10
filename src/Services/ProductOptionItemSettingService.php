<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionItemHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;

class ProductOptionItemSettingService
{
    /** @var ProductOptionItemHandler $productOptionItemHandler */
    protected $productOptionItemHandler;

    /**
     * ProductOptionItemSettingService constructor.
     */
    public function __construct()
    {
        $this->productOptionItemHandler = app('xero_commerce.productOptionItemHandler');
    }

    /**
     * @param Request $request   request
     * @param int     $productId productId
     *
     * @return void
     */
    public function defaultOptionStore(Request $request, $productId)
    {
        $productArgs = $request->all();

        $productOptionItemArgs = [];
        $productOptionItemArgs['product_id'] = $productId;
        $productOptionItemArgs['option_type'] = Product::OPTION_TYPE_SIMPLE;
        $productOptionItemArgs['name'] = $productArgs['name'];
        $productOptionItemArgs['addition_price'] = 0;
        $productOptionItemArgs['stock'] = $productArgs['stock'];
        $productOptionItemArgs['alert_stock'] = $productArgs['alert_stock'];

        $displayState = ProductOptionItem::DISPLAY_VISIBLE;
        if ($productArgs['state_display'] != Product::DISPLAY_VISIBLE) {
            $displayState = ProductOptionItem::DISPLAY_HIDDEN;
        }
        $productOptionItemArgs['state_display'] = $displayState;

        $dealState = ProductOptionItem::DEAL_ON_SALE;
        if ($productArgs['state_deal'] != Product::DEAL_ON_SALE) {
            $dealState = ProductOptionItem::DEAL_END;
        }
        $productOptionItemArgs['state_deal'] = $dealState;

        $this->productOptionItemHandler->store($productOptionItemArgs);
    }

    /**
     * @param Request $request
     * @param $productId
     */
    public function saveOptionItems(Request $request, $productId)
    {
        // 기존 옵션들은 삭제
        $this->removeProductOptionItems($productId);
        // 새로운 옵션들을 입력
        $optionItemsData = $request->get('option_items');
        // 저장
        foreach ($optionItemsData as $itemData) {
            $itemData['product_id'] = $productId;
            $this->productOptionItemHandler->store($itemData);
        }
    }

    public function removeProductOptionItems($productId)
    {
        $items = ProductOptionItem::where('product_id', $productId)->get();

        foreach ($items as $item) {
            $this->productOptionItemHandler->destroy($item);
        }
    }
}
