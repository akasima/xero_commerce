<?php

namespace Xpressengine\Plugins\XeroStore\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroStore\Handlers\ProductOptionItemHandler;
use Xpressengine\Plugins\XeroStore\Models\Product;
use Xpressengine\Plugins\XeroStore\Models\ProductOptionItem;

class ProductOptionItemSettingService
{
    /** @var ProductOptionItemHandler $productOptionItemHandler */
    protected $productOptionItemHandler;

    /**
     * ProductOptionItemSettingService constructor.
     */
    public function __construct()
    {
        $this->productOptionItemHandler = app('xero_store.productOptionItemHandler');
    }

    public function defaultOptionStore(Request $request, $productId)
    {
        $productArgs = $request->all();

        $productOptionItemArgs = [];
        $productOptionItemArgs['product_id'] = $productId;
        $productOptionItemArgs['option_type'] = ProductOptionItem::TYPE_OPTION_ITEM;
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
}
