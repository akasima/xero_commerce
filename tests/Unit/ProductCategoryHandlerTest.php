<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 12:27 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Xpressengine\Plugins\XeroCommerce\Handlers\ProductCategoryHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\ProductCategory;

class ProductCategoryHandlerTest extends DefaultSet
{

    public function testStore()
    {
        $handler = new ProductCategoryHandler();
        $product = $this->makeProduct();
        $handler->store($product->id,[1]);
        $this->assertNotEquals(0,ProductCategory::where('product_id',$product->id)->count());
    }

    public function testRemove()
    {
        $handler = new ProductCategoryHandler();
        $this->testStore();
        $product = Product::first();
        $handler->remove($product->id);
        $this->assertEquals(0,ProductCategory::where('product_id',$product->id)->count());
    }

    public function testGetIds()
    {
        $handler = new ProductCategoryHandler();
        $product = $this->makeProduct();
        $list = $handler->getIds($product->id);
        $this->assertTrue(is_iterable($list));
    }
}
