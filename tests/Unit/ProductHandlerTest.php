<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 10/12/2018
 * Time: 7:30 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Xpressengine\Http\Request;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Test\Unit\DefaultSet;

class ProductHandlerTest extends DefaultSet
{


    public function testGetProduct()
    {
        $product = $this->makeProduct();
        $handler = new ProductHandler();
        $product = $handler->getProduct($product->id);
        $this->assertNotNull($product);
    }

    public function testGetSortAble()
    {
        $sort_list = ProductHandler::getSortAble();
        $this->assertTrue(is_iterable($sort_list));
    }

    public function testStore()
    {
        $handler = new ProductHandler();
        $args = [
            'shop_id' => 1,
            'product_code'=>'test',
            'infoKeys'=>['test'],
            'infoValues'=>['hello'],
            'name'=>'test',
            'sub_name'=>'test',
            'original_price'=>1000,
            'sell_price'=>1000,
            'discount_percentage'=>100,
            'description'=>'test',
            'tax_type'=>1,
            'state_display'=>1,
            'state_deal'=>1,
            'shop_delivery_id'=>1,
            'images'=>[]
        ];
        $product = $handler->store($args);
        $this->assertNotNull($product);
    }

    public function testGetProductsQueryForSetting()
    {
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')->andReturn([
            'product_name'=>'test',
            'product_code'=>'test',
            'product_deal_state'=>0,
            'product_display_state'=>0,
            'product_tax_type'=>0
        ]);
        $handler = new ProductHandler();
        $query = $handler->getProductsQueryForSetting($request);
        $ormcollection=$query->get();
        $this->assertInstanceOf(Collection::class, $ormcollection);
    }

    public function testSetPublish()
    {
        $handler = new ProductHandler();
        $product = $this->makeProduct();
        $handler->setPublish($product->id,true);
        $this->assertTrue((bool)Product::find($product->id)->publish);
        $handler->setPublish($product->id,false);
        $this->assertFalse((bool)Product::find($product->id)->publish);
    }

    public function testGetProductsQueryForWidget()
    {
        $handler = new ProductHandler();
        $request = \Mockery::mock(Request::class);
        $request->shouldReceive('all')->andReturn([]);
        $query = $handler->getProductsQueryForWidget($request);
        $ormcollection=$query->get();
        $this->assertInstanceOf(Collection::class, $ormcollection);
    }

    public function testDestroy()
    {
        $handler = new ProductHandler();
        $product = $this->makeProduct();
        $handler->destroy($product);
        $this->assertNull(Product::find($product->id));
    }

    public function testUpdate()
    {
        $handler = new ProductHandler();
        $product=$this->makeProduct();
        $args = [
            'name'=>'updateTest',
            'sub_name'=>'test',
            'images'=>[]
        ];
        $handler->update($product, $args);
        $this->assertEquals('updateTest',Product::find($product->id)->name);
    }
}
