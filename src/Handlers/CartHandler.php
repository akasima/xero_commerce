<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\XeroCommerce\Goods;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;
use Xpressengine\User\Models\User;

class CartHandler
{
    public function getCartList()
    {
        return $this->getCartQuery()->get();
    }

    public function getCartListByProductIds($product_ids)
    {
        if (is_null($product_ids)) {
            return collect([]);
        }
        return $this->getCartQuery()->whereIn('product_id', $product_ids)->get();
    }

    private function getCartQuery()
    {
        return Cart::where('user_id', Auth::id() ?: User::first()->id)->with('option.product.store.deliveryCompanys');
    }

    public function addCart(ProductOptionItem $option, $count = 1)
    {
        $cart = new Cart();
        $cart->user_id = Auth::id() ?: User::first()->id;
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

    public function cartSummary($product_ids = null)
    {
        $carts = ($product_ids)? $this->getCartListByProductIds($product_ids) :$this->getCartList();
        $storeCarts = $carts->groupBy('option.product.store_id');
        $origin =
            $carts->sum('option.product.original_price') +
            $carts->sum('option.product.addition_price');
        $sell = $carts->sum('option.product.sell_price');
        $fare = $storeCarts->sum(function ($item) {
            return $item->first()->option->product->store->deliveryCompanys()->first()->pivot->delivery_fare;
        });
        $sum = $origin-$sell+$fare;
        return [
            'original_price' => $origin,
            'sell_price' => $sell,
            'fare' => $fare,
            'sum' => $sum
        ];
    }
}
