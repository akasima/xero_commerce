<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 10/12/2018
 * Time: 10:56 AM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Cart;
use Xpressengine\Plugins\XeroCommerce\Models\CartGroup;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;

class CartHandlerTest extends DefaultSet
{

    public function testGetCartList()
    {
        $cartHandler = new CartHandler();
        $list = $cartHandler->getCartList();
        $this->assertTrue(is_iterable($list));
    }

    public function testGetCartListByCartIds()
    {
        $cartHandler = new CartHandler();
        $list = $cartHandler->getCartListByCartIds(1);
        $this->assertTrue(is_iterable($list));
        $list = $cartHandler->getCartListByCartIds([1]);
        $this->assertTrue(is_iterable($list));
    }

    public function testMakeCartGroup()
    {
        $this->makeProduct();
        $cartHandler = new CartHandler();
        $sellUnit = ProductOptionItem::first();
        $cartGroup = $cartHandler->makeCartGroup($sellUnit, [], 4);
        $this->assertInstanceOf(CartGroup::class, $cartGroup);
    }

    public function testAddCart()
    {
        $handler = new CartHandler();
        $this->makeProduct();
        $cartGroup = $handler->makeCartGroup(ProductOptionItem::first(), 4);
        $cart = $handler->addCart(Product::first(), collect([$cartGroup]), '선불');
        $this->assertNotNull($cart);
        $this->assertInstanceOf(Cart::class, $cart);
        return $cart;
    }

    /**
     * @depends  testAddCart
     */
    public function testChangeCartItem(Cart $cart)
    {
        $handler = new CartHandler();
        $original = $cart->sellGroups()->get();
        $id = $cart->id;
        $this->makeProduct();
        $handler->changeCartItem($cart, $handler->makeCartGroup(ProductOptionItem::first(), 2), '착불');
        $this->assertNotEquals($original, $cart->sellGroups()->get());
        $handler->changeCartItem($cart, $handler->makeCartGroup(ProductOptionItem::first(), 0), '착불');
        $this->assertNull(Cart::find($id));
    }

    /**
     * @depends testAddCart
     */
    public function testDrawCart()
    {
        $handler = new CartHandler();
        $this->makeProduct();
        $cartGroup = $handler->makeCartGroup(ProductOptionItem::first(), 4);
        $cart = $handler->addCart(Product::first(), collect([$cartGroup]), '선불');
        $handler->drawCart($cart->id);
        $this->assertNull(Cart::find($cart->id));
    }

    public function testResetCart()
    {
        $handler = new CartHandler();
        $this->makeProduct();
        $cartGroup = $handler->makeCartGroup(ProductOptionItem::first(), 4);
        $cart = $handler->addCart(Product::first(), collect([$cartGroup]), '선불');
        $handler->resetCart();
        $this->assertEquals(0, $handler->getCartList()->count());
    }

    public function testSellSetList()
    {
        $handler = new CartHandler();
        $list = $handler->getSellSetList();
        $this->assertTrue(is_iterable($list));
    }

    public function testGetSummary()
    {
        $handler = new CartHandler();
        $summary = $handler->getSummary();
        $this->assertArrayHasKey('original_price',$summary);
        $this->assertArrayHasKey('sell_price',$summary);
        $this->assertArrayHasKey('discount_price',$summary);
        $this->assertArrayHasKey('fare',$summary);
        $this->assertArrayHasKey('sum',$summary);
    }

}
