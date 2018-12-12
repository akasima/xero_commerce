<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 2:26 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Xpressengine\Plugins\XeroCommerce\Handlers\WishHandler;
use PHPUnit\Framework\TestCase;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\Wish;

class WishHandlerTest extends DefaultSet
{

    public function testStore()
    {
        $handler = new WishHandler();
        $product = $this->makeProduct();
        $handler->store($product);
        $this->assertNotEquals(0, Wish::count());
    }

    public function testStoreMany()
    {
        $handler = new WishHandler();
        $product = $this->makeProduct();
        $handler->storeMany(Product::all());
        $this->assertNotEquals(0, Wish::count());
    }

    public function testIsWish()
    {
        $handler = new WishHandler();
        $this->testStore();
        $product = Product::first();
        $result = $handler->isWish($product);
        $this->assertTrue($result);

    }

    public function testList()
    {
        $handler = new WishHandler();
        $list = $handler->list();
        $this->assertTrue(is_iterable($list));
    }

    public function testRemoveByModel()
    {
        $handler = new WishHandler();
        $this->testStore();
        $wish = Wish::first();
        $id = $wish->id;
        $handler->removeByModel($wish);
        $this->assertNull(Wish::find($id));
    }

    public function testRemove()
    {
        $handler = new WishHandler();
        $this->testStore();
        $product =Product::first();
        $handler->remove($product);
    }

    public function testRemoveMany()
    {
        $handler = new WishHandler();
        $this->testStore();
        $handler->removeMany(Wish::pluck('id'));
    }
}
