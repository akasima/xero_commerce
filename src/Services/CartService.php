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
        return $this->getList()->map(function(Cart $cart){
            return $cart->getJsonFormat();
        });
    }

    public function addList(Request $request, SellType $sellType, SellUnit $sellUnit)
    {
        $parms = $request->get('sell_units');
        $cartGroupList = collect($parms)->map(function($parm) use($sellUnit){
           return $this->cartHandler->makeCartGroup($sellUnit->find($parm['unit_id']), $parm['count']);
        });
        return $this->cartHandler->addCart($sellType->find($parms['sell_type_id']), $cartGroupList);
    }

    public function drawList(Cart $cart)
    {
        return $this->cartHandler->drawCart($cart->id);
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
        if(is_null($ids)){
            $ids = [];
        }
        return $this->cartHandler->getSummary($this->cartHandler->getCartListByCartIds($ids));
    }

}