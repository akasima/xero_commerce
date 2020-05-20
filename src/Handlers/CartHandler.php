<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\CartItem;
use Xpressengine\Plugins\XeroCommerce\Models\ProductVariant;

class CartHandler extends OrderableItemHandler
{
    private function getUserId()
    {
        return Auth::id() ? Auth::id() : request()->ip();
    }

    public function getCartItems()
    {
        return $this->getCartItemQuery()->get();
    }

    private function getCartItemQuery()
    {
        return CartItem::where('user_id', $this->getUserId())->latest();
    }

    public function getSellSetList()
    {
        return $this->getCartItems();
    }

    public function makeCartItem(ProductVariant $variant, array $customValues, $count)
    {
        $cartItem = new CartItem();
        $cartItem->user_id = $this->getUserId();
        $cartItem->setCount($count);
        $cartItem->setCustomOptions($customValues);
        $cartItem->product_id = $variant->product_id;
        $cartItem->productVariant()->associate($variant);
        $cartItem->save();

        return $cartItem;
    }

    public function changeCartItem(CartItem $cartItem, Request $request)
    {
        $cartItem->fill($request->all());
        $cartItem->save();
    }

    public function deleteCartItem($cartItemId)
    {
        if (is_array( $cartItemId ) || ( is_object( $cartItemId ) && ( $cartItemId instanceof \Traversable ))) {
            return CartItem::whereIn('id', $cartItemId)->delete();
        }
        return CartItem::where('id', $cartItemId)->delete();
    }

    /**
     * 장바구니 비우기
     * @return mixed
     */
    public function resetCart()
    {
        return $this->getCartItems()->delete();
    }
}
