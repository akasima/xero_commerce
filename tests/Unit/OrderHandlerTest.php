<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 07/12/2018
 * Time: 5:27 PM
 */
namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Query\Builder;
use Xpressengine\Plugins\XeroCommerce\Handlers\OrderHandler;
use Xpressengine\Plugins\XeroCommerce\Models\DeliveryCompany;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;

class OrderHandlerTest extends DefaultSet
{

    public function testGetOrderItemList()
    {
        $handler =  new OrderHandler();
        $order = new \Xpressengine\Plugins\XeroCommerce\Models\Order();
        $query = $handler->getOrderItemList($order);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $query);
    }

    public function testMakeOrder()
    {
        $handler = new OrderHandler();
        $order = $handler->makeOrder();
        $this->assertInstanceOf(\Xpressengine\Plugins\XeroCommerce\Models\Order::class,$order);
    }

    public function testRegister()
    {
        $orderHandler = new OrderHandler();
        $cartHandler =  new \Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler();
        $this->makeProduct();
        $cart = $cartHandler->addCart(Product::first(), $cartHandler->makeCartGroup(ProductOptionItem::first(),2),'선불');
        $order = $orderHandler->register(collect([$cart]));
        $this->assertNotEquals(0, $order->orderItems()->count());
        return $order;
    }

    public function testPaidCheck()
    {
        $orderHandler = new OrderHandler();
        $order = $this->testRegister();
        $check = $orderHandler->paidCheck($order);
        $this->assertEquals(Order::TEMP, $order->code);
    }

    public function testAfterServiceCheck()
    {
        $orderHandler = new OrderHandler();
        $order = $this->testRegister();
        $check = $orderHandler->afterServiceCheck($order);
        $this->assertNull($check);
    }

    public function testDeliveryCheck()
    {
        $orderHandler = new OrderHandler();
        $order = $this->testRegister();
        $check = $orderHandler->deliveryCheck($order);
        $this->assertNull($check);
    }

    public function testUpdate()
    {
        $orderHandler = new OrderHandler();
        $order = $this->testRegister();
        $order->code =Order::COMPLETE;
        $order = $orderHandler->update($order);
        $this->assertEquals(0, count($order->getDirty()));
    }

    public function testMakeDelivery()
    {
        $orderHandler = new OrderHandler();
        $cartHandler =  new \Xpressengine\Plugins\XeroCommerce\Handlers\CartHandler();
        $product = $this->makeProduct();
        $this->makeShop();
        $cart = $cartHandler->addCart($product, $cartHandler->makeCartGroup(ProductOptionItem::first(), [], 2),'선불');
        $order = $orderHandler->register(collect([$cart]));
        $order = $orderHandler->makeDelivery($order, [
            'delivery' => [
                'nickname'=>'test',
                'name'=>'test',
                'phone'=>'t010',
                'addr'=>'test',
                'addr_detail'=>'test',
                'addr_post'=>'00000',
                'msg'=>'test'
            ]
        ]);
        $delivery_count = $order->orderItems()->whereHas('delivery')->count();
        $this->assertNotEquals(0, $delivery_count);
        return $order;
    }

    public function testMakePayment()
    {
        $handler = new OrderHandler();
        $order = $this->testMakeDelivery();
        $payment_order = $handler->makePayment($order);
        $this->assertNotEquals(Order::TEMP, $payment_order->code);
        return $payment_order;
    }

    public function testCompleteDelivery()
    {
        $handler = new OrderHandler();
        $order = $this->testMakePayment();
        $order->orderItems()->each(function(OrderItem $orderItem){
            $sellType = $orderItem->sellType;
            $sellType->delivery()->associate(DeliveryCompany::first());
        });
        $order->orderItems->each(function(OrderItem $orderItem) use($handler){
            $handler->completeDelivery($orderItem);
        });
        $order = $handler->update($order);

        $this->assertEquals(\Xpressengine\Plugins\XeroCommerce\Models\Order::COMPLETE, $order->code);
    }

    public function testIdUpdate()
    {
        $order =$this->testRegister();
        $order->user_id='notfound';
        $handler = new OrderHandler();
        $handler->idUpdate($order);
        $this->assertNotEquals('notfound', $order->user_id);
    }

    public function testChangeOrderItem()
    {
        $handler = new OrderHandler();
        $order=$this->testMakePayment();
        $orderItem = $order->orderItems()->first();
        $afterOrder = $handler->changeOrderItem($orderItem, OrderItem::EXCHANGED);
        $this->assertNotEquals(Order::ORDERED, $afterOrder->code);
    }

    public function testOrderCancel()
    {
        $handler = new OrderHandler();
        $order=$this->testMakePayment();
        $afterOrder = $handler->orderCancel($order,'test');
        $this->assertEquals(Order::CANCELED, $afterOrder->code);
    }

    public function testGetSellSetList()
    {
        $handler = new OrderHandler();
        $order=$this->testMakePayment();
        $list = $handler->getSellSetList();
        $this->assertTrue(is_iterable($list));
    }

    public function testGetAfterserviceList()
    {
        $handler = new OrderHandler();
        $order=$this->testMakePayment();
        $list = $handler->getAfterserviceList();
        $this->assertTrue(is_iterable($list));
    }

    public function testGetOrderList()
    {
        $handler = new OrderHandler();
        $order=$this->testMakePayment();
        $list = $handler->getOrderList(1,1);
        $this->assertInstanceOf(Paginator::class,$list);
    }

    public function testShipNoRegister()
    {
        $handler = new OrderHandler();
        $order=$this->testMakePayment();
        $order_item= $order->orderItems()->first();
        $updated_order = $handler->shipNoRegister($order_item, 'test');
        $this->assertNotEquals(Order::ORDERED,$updated_order->code);

    }

    public function testDailyBoard()
    {
        $handler = new OrderHandler();
        $board = $handler->dailyBoard();
        $this->assertArrayHasKey('days',$board);
        $this->assertArrayHasKey('count',$board);
    }

    public function testWhereUser()
    {
        $handler = new OrderHandler();
        $query = $handler->whereUser()->select();
        $this->assertInstanceOf(\Xpressengine\Database\Eloquent\Builder::class, $query);
    }

    public function testDashboard()
    {
        $handler = new OrderHandler();
        $dash = $handler->dashboard();
        $this->assertEquals(count(Order::STATUS), count($dash));
    }
}
