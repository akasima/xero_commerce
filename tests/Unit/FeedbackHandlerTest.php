<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 12:02 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Xpressengine\Plugins\XeroCommerce\Handlers\FeedbackHandler;
use PHPUnit\Framework\TestCase;
use Xpressengine\Plugins\XeroCommerce\Models\FeedBack;
use Xpressengine\Plugins\XeroCommerce\Models\Product;

class FeedbackHandlerTest extends DefaultSet
{


    public function testStore()
    {
        $handler = new FeedbackHandler();
        $product = $this->makeProduct();
        $args=[
            'title'=>'test',
            'content'=>'test',
            'score'=>rand(0,10)
        ];
        $handler->store($product,$args);
        $this->assertNotEquals(0,$product->feedback()->count());
    }

    public function testGet()
    {
        $this->testStore();
        $handler = new FeedbackHandler();
        $product = Product::first();
        $list = $handler->get($product);
        $this->assertNotEquals(0,$list->count());
    }

    public function testUpdate()
    {
        $handler = new FeedbackHandler();
        $this->testStore();
        $feedback = FeedBack::first();
        $args=[
            'content'=>'updateTest'
        ];
        $handler->update($feedback,$args);
        $this->assertEquals('updateTest',FeedBack::find($feedback->id)->content);
    }

    public function testDelete()
    {
        $handler = new FeedbackHandler();
        $this->testStore();
        $feedback = FeedBack::first();
        $feedback_id = $feedback->id;
        $handler->delete($feedback);
        $this->assertNull(FeedBack::find($feedback_id));
    }
}
