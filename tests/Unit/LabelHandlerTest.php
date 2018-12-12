<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 12:02 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Xpressengine\Plugins\XeroCommerce\Handlers\LabelHandler;
use PHPUnit\Framework\TestCase;
use Xpressengine\Plugins\XeroCommerce\Models\Label;
use Xpressengine\Plugins\XeroCommerce\Models\Product;

class LabelHandlerTest extends DefaultSet
{

    public function testStore()
    {
        $handler = new LabelHandler();
        $args=[
            'name'=>'test',
            'eng_name'=>'test',
            'background_color'=>'test',
            'text_color'=>'test'
        ];
        $handler->store($args);
        $this->assertNotEquals(0,Label::count());
    }

    public function testStoreProductLabel()
    {
        $handler = new LabelHandler();
        $product = $this->makeProduct();
        $this->testStore();
        $handler->storeProductLabel($product->id,Label::pluck('id')->all());
        $this->assertNotEquals(0,$product->labels()->count());
        return $product;
    }

    public function testUpdate()
    {
        $handler = new LabelHandler();
        $this->testStore();
        $label=Label::first();
        $args=[
            'name'=>'updateTest'
        ];
        $handler->update($label,$args);
        $this->assertEquals('updateTest',Label::find($label->id)->name);

    }

    public function testGetLabels()
    {
        $handler = new LabelHandler();
        $list = $handler->getLabels();
        $this->assertTrue(is_iterable($list));
    }

    public function testDestroy()
    {
        $handler = new LabelHandler();
        $this->testStore();
        $label=Label::first();
        $label_id = $label->id;
        $handler->destroy($label->id);
        $this->assertNull(Label::find($label_id));
    }

    public function testDestroyProductLabel()
    {
        $product = $this->testStoreProductLabel();

        $handler = new LabelHandler();
        $handler->destroyProductLabel($product->id);
        $this->assertEquals(0,$product->labels()->count());

    }

    public function testGetLabel()
    {
        $handler = new LabelHandler();
        $this->testStore();
        $label_id =Label::first()->id;
        $result = $handler->getLabel($label_id);
        $this->assertInstanceOf(Label::class,$result);
        $result = $handler->getLabel(0);
        $this->assertNull($result);
    }
}
