<?php

namespace Xpressengine\Plugins\XeroStore\Handlers;

use Xpressengine\Plugins\XeroStore\Goods;
use Xpressengine\Plugins\XeroStore\Models\Cart;
use Xpressengine\Plugins\XeroStore\Models\ProductOptionItem;
use Xpressengine\User\Models\User;

class CartHandler
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getCartList()
    {
        return Cart::where('user_id', $this->user->getId())->with('option.product')->get();
    }

    public function addCart(ProductOptionItem $option, $count = 1)
    {
        $cart = new Cart();
        $cart->user_id = $this->user->id;
        $cart->product_id = $option->product_id;
        $cart->option_id = $option->id;
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

    public function orderCart()
    {
        $carts = $this->getCartList();
        return $carts->map(function (Cart $cart) {
            $goods = new Goods($cart->option, $cart->count);
            return $goods;
        })->toArray();
    }
}
