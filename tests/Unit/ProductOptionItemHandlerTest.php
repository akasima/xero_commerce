<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 12:27 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Xpressengine\Plugins\XeroCommerce\Handlers\ProductOptionItemHandler;
use PHPUnit\Framework\TestCase;
use Xpressengine\Plugins\XeroCommerce\Models\ProductOptionItem;

class ProductOptionItemHandlerTest extends DefaultSet
{

    public function testStore()
    {
        $handler = new ProductOptionItemHandler();
        $args = [
            'product_id'=>1,
            'option_type'=>ProductOptionItem::TYPE_ADDITION_ITEM,
            'name'=>'test',
            'addition_price'=>0,
            'stock'=>10,
            'alert_stock'=>0,
            'state_display'=>0,
            'state_deal'=>0
        ];
        $handler->store($args);
        $this->assertNotEquals(0,ProductOptionItem::count());
    }

    public function testGetOptionItem()
    {
        $handler = new ProductOptionItemHandler();
        $this->testStore();
        $item = ProductOptionItem::first();
        $item = $handler->getOptionItem($item->id);
        $this->assertNotNull($item);
    }

    public function testUpdate()
    {
        $handler = new ProductOptionItemHandler();
        $this->testStore();
        $item = ProductOptionItem::first();
        $id = $item->id;
        $args = [
            'name'=>'updateTest'
            ];
        $handler->update($item, $args);
        $this->assertEquals('updateTest',ProductOptionItem::find($id)->name);
    }

    public function testDestroy()
    {
        $handler = new ProductOptionItemHandler();
        $this->testStore();
        $item = ProductOptionItem::first();
        $id = $item->id;
        $handler->destroy($item);
        $this->assertNull(ProductOptionItem::find($id));

    }
}
