<?php

namespace Xpressengine\Plugins\XeroStore\Handlers;

use Xpressengine\Plugins\XeroStore\Goods;
use Xpressengine\Plugins\XeroStore\Models\Cart;
use Xpressengine\Plugins\XeroStore\Option;
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
        return Cart::where('user_id', $this->user->getId())->with('option')->get();
    }

    public function addCart(Option $option)
    {
        $cart = new Cart();
        $cart->user_id = $this->user->getId();
        $cart->product_id = $option->product->id;
        $cart->option_id = $option->id;
        $cart->count = 1;
        $cart->save();
    }

    public function drawCart($cart_id)
    {
        return Cart::find($cart_id)->delete();
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
