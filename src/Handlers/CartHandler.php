<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Support\Facades\Auth;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\CartGroup;
use Xpressengine\Plugins\XeroCommerce\Models\SellType;
use Xpressengine\Plugins\XeroCommerce\Models\SellUnit;
use Xpressengine\User\Models\User;

class CartHandler extends SellSetHandler
{
    public function getCartList()
    {
        return $this->getCartQuery()->get();
    }

    public function getCartListByCartIds($cart_ids)
    {
        if (is_iterable($cart_ids)) {
            return $this->getCartQuery()->whereIn('id', $cart_ids)->get();
        }else{
            return $this->getCartQuery()->where('id', $cart_ids)->get();
        }
    }

    private function getCartQuery()
    {
        if (is_null($user_id = Auth::id())) {
            $user_id = \request()->ip();
        }
        return Cart::where('user_id', $user_id)->latest();
    }

    public function addCart(SellType $sellType, $cartGroupList, $delivery)
    {
        $cart = new Cart();

        $cart->user_id = Auth::id() ?: \request()->ip();
        $cart->delivery_pay = Cart::DELIVERY[$delivery];
        $sellType->carts()->save($cart);
        $cart->save();
        $cartGroupList->each(function (CartGroup $cartGroup) use ($cart) {
            $cart->addGroup($cartGroup);
        });

        return $cart;
    }

    public function drawCart($cart_id)
    {
        if (is_array( $cart_id ) || ( is_object( $cart_id ) && ( $cart_id instanceof \Traversable ))) {
            CartGroup::whereIn('cart_id', $cart_id)->delete();

            return Cart::whereIn('id', $cart_id)->delete();
        }
        CartGroup::where('cart_id', $cart_id)->delete();

        return Cart::find($cart_id)->delete();
    }

    public function resetCart()
    {
        $cart_ids = $this->getCartList()->pluck('id');
        return $this->drawCart($cart_ids);
    }

    public function getSellSetList()
    {
        return $this->getCartList();
    }

    public function makeCartGroup(SellUnit $sellUnit, array $customValues, $count)
    {
        $cartGroup = new CartGroup();
        $cartGroup->cart_id = 0;
        $cartGroup->setCount($count);
        $cartGroup->setCustomValues($customValues);
        $sellUnit->cartGroup()->save($cartGroup);
        $cartGroup->save();

        return $cartGroup;
    }

    public function changeCartItem(Cart $cart, $cartGroupList, $delivery)
    {
        $cart->sellGroups()->delete();
        $cart->delivery_pay = Cart::DELIVERY[$delivery];

        $cartGroupList->each(function (CartGroup $cartGroup) use ($cart) {
            $cart->addGroup($cartGroup);
        });
        $cart->save();

        if ($cart->getCount() == 0) {
            $cart->delete();
        }
    }
}
