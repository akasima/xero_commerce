<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 12:28 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Xpressengine\Plugins\XeroCommerce\Handlers\QnaHandler;
use PHPUnit\Framework\TestCase;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\Qna;

class QnaHandlerTest extends DefaultSet
{

    public function testStore()
    {
        $handler = new QnaHandler();
        $product = $this->makeProduct();
        $args=[
            'title'=>'test',
            'content'=>'test'
        ];
        $handler->store($product,$args);
        $this->assertNotEquals(0,$product->qna()->count());
    }

    public function testGet()
    {
        $this->testStore();
        $handler = new QnaHandler();
        $product = Product::first();
        $list = $handler->get($product);
        $this->assertNotEquals(0,$list->count());
    }

    public function testUpdate()
    {
        $handler = new QnaHandler();
        $this->testStore();
        $qna = Qna::first();
        $args=[
            'content'=>'updateTest'
        ];
        $handler->update($qna,$args);
        $this->assertEquals('updateTest',Qna::find($qna->id)->content);
    }

    public function testDelete()
    {
        $handler = new QnaHandler();
        $this->testStore();
        $feedback = Qna::first();
        $feedback_id = $feedback->id;
        $handler->delete($feedback);
        $this->assertNull(Qna::find($feedback_id));
    }
}
