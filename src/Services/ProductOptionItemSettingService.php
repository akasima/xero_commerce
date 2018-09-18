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
        $productOptionItemArgs['option_type'] = ProductOptionItem::TYPE_DEFAULT_OPTION;
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

    public function removeProductOptionItems($productId)
    {
        $items = ProductOptionItem::where('product_id', $productId)->get();

        foreach ($items as $item) {
            $this->productOptionItemHandler->destroy($item);
        }
    }
}
