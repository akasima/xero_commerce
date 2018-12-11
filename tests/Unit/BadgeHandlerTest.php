<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 12:02 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Xpressengine\Plugins\XeroCommerce\Handlers\BadgeHandler;
use PHPUnit\Framework\TestCase;
use Xpressengine\Plugins\XeroCommerce\Models\Badge;

class BadgeHandlerTest extends DefaultSet
{

    public function testStore()
    {
        $handler = new BadgeHandler();
        $args=[
            'name'=>'test',
            'eng_name'=>'test',
            'background_color'=>'test',
            'text_color'=>'test'
        ];
        $handler->store($args);
        $this->assertNotEquals(0, Badge::count());
    }

    public function testUpdate()
    {
        $this->testStore();
        $badge=Badge::first();
        $handler = new BadgeHandler();
        $handler->update($badge,['name'=>'updateTest']);
        $this->assertEquals('updateTest',Badge::find($badge->id)->name);
    }

    public function testDestroy()
    {
        $handler = new BadgeHandler();
        $this->testStore();
        $badge=Badge::first();
        $handler->destroy($badge->id);
        $this->assertNull(Badge::find($badge->id));

    }
}
