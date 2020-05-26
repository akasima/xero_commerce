<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\CartItem;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\SellUnit;

class CartService
{
    /**
     * @var CartHandler
     */
    protected $cartHandler;

    public function __construct()
    {
        $this->cartHandler = app('xero_commerce.cartHandler');
    }

    public function getList()
    {
        return $this->cartHandler->getCartItems();
    }

    public function getJsonList()
    {
        return $this->getList()->map(function (CartItem $cartItem) {
            return $cartItem->toArray();
        });
    }

    public function addList(Request $request, Product $product)
    {
        $items = $request->get('items');
        $cartItemList = collect($items)->map(function ($item) use ($product) {

            $variantId = $item['variant']['id'];
            $customValues = array_get($item, 'custom_options', []);
            $count = $item['count'];

            $existingItem = $this->cartHandler->getCartItems()
                ->where('product_variant_id', $variantId)
                ->where('custom_options', $customValues)
                ->where('count', $count)
                ->first();

            // 똑같은 품목+옵션이 존재하면
            if($existingItem) {
                // 카운트 올리고 저장
                $existingItem->count = $existingItem->count + $count;
                $existingItem->save();
                return $existingItem;
            }

            return $this->cartHandler->makeCartItem($product->variants()->find($item['variant']['id']), array_get($item, 'custom_options', []), $item['count']);
        });

        return $cartItemList;
    }

    public function delete(CartItem $cartItem)
    {
        return $this->cartHandler->deleteCartItem($cartItem->id);
    }

    public function deleteList($cartItemIds)
    {
        return $this->cartHandler->deleteCartItem($cartItemIds);
    }

    public function resetList()
    {
        return $this->cartHandler->resetCart();
    }

    public function summary(Request $request)
    {
        return $this->cartHandler->getSummary($this->cartHandler->getCartItems());
    }

    public function change(CartItem $cartItem, Request $request)
    {
        return $this->cartHandler->changeCartItem($cartItem, $request);
    }
}
