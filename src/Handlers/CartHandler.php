<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\OrderUnit;
use Xpressengine\User\Models\User;

class CartHandler extends OrderUnitHandler
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

    public function addCart(OrderUnit $orderable, $count = 1)
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

    public function getGoodsList()
    {
        return $this->getCartList();
    }
}
