<?php

namespace Xpressengine\Plugins\XeroCommerce\Services;

use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\SellType;
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
        return $this->cartHandler->getCartList();
    }

    public function getJsonList()
    {
        return $this->getList()->map(function (Cart $cart) {
            return $cart->getJsonFormat();
        });
    }

    public function addList(Request $request, SellType $sellType)
    {
        $parms = $request->get('options');
        $cartGroupList = collect($parms)->map(function ($parm) use ($sellType) {
            return $this->cartHandler->makeCartGroup($sellType->sellUnits()->find($parm['unit']['id']), $parm['custom_values'], $parm['count']);
        });

        return $this->cartHandler->addCart($sellType, $cartGroupList, $request->get('delivery'));
    }

    public function draw(Cart $cart)
    {
        return $this->cartHandler->drawCart($cart->id);
    }

    public function drawList($cart_ids)
    {
        return $this->cartHandler->drawCart($cart_ids);
    }

    public function resetList()
    {
        return $this->cartHandler->resetCart();
    }

    public function getCartsById($cart_ids)
    {
        return $this->cartHandler->getCartListByCartIds($cart_ids);
    }

    public function summary(Request $request)
    {
        $ids = $request->get('cart_ids');

        if (is_null($ids)) {
            $ids = [];
        }

        return $this->cartHandler->getSummary($this->cartHandler->getCartListByCartIds($ids));
    }

    public function change(Cart $cart, Request $request)
    {
        $cartGroupList = collect($request->choose)->map(function ($parm) use ($cart) {
            return $this->cartHandler->makeCartGroup($cart->sellType->sellUnits()->find($parm['unit']['id']), $parm['custom_values'], $parm['count']);
        });

        return $this->cartHandler->changeCartItem($cart, $cartGroupList, $request->get('pay'));
    }
}
