<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroCommerce\Goods;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\Orderable;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\User\Models\User;

class CartHandler
{
    public function getCartList()
    {
        return $this->getCartQuery()->get();
    }

    public function getCartListByCartIds($cart_ids)
    {
        return $this->getCartQuery()->whereIn('id', $cart_ids)->get();
    }

    private function getCartQuery()
    {
        return Cart::where('user_id', Auth::id() ?: User::first()->id)->with('orderable');
    }

    public function addCart(Orderable $orderable, $count = 1)
    {
        $cart = new Cart();
        $cart->user_id = Auth::id() ?: User::first()->id;
        $orderable->goods()->save($cart);
        $cart->count = $count;
        $cart->save();
    }

    public function drawCart($cart_id)
    {
        if (is_iterable($cart_id)) {
            return Cart::whereIn('id', $cart_id)->delete();
        }
        return Cart::find($cart_id)->delete();
    }

    public function resetCart()
    {
        $cart_ids = $this->getCartList()->pluck('id');
        return $this->drawCart($cart_ids);
    }

    public function cartSummary($product_ids = null)
    {
        $carts = $this->getCartList();
        $storeCarts = $carts->groupBy(function($cart){
            return $cart->orderable->getStore()->id;
        });
        $origin =
            $carts->sum(function (Cart $cart) {
                return $cart->getOriginalPrice();
            });
        $sell = $carts->sum(function (Cart $cart) {
            return $cart->getSellPrice();
        });
        $fare = $storeCarts->sum(function ($storeItems) {
            $totalPrice = $storeItems->sum(function($cart){
                return $cart->getSellPrice();
            });
            return $storeItems->first()->calculateFare($totalPrice);
        });
        $sum = $sell + $fare;
        return [
            'original_price' => $origin,
            'sell_price' => $sell,
            'fare' => $fare,
            'sum' => $sum
        ];
    }
}
