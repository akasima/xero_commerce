<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 2:26 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Xpressengine\Plugins\XeroCommerce\Handlers\ShopUserHandler;
use PHPUnit\Framework\TestCase;
use Xpressengine\Plugins\XeroCommerce\Models\ShopUser;

class ShopUserHandlerTest extends DefaultSet
{

    public function testStore()
    {
        $handler = new ShopUserHandler();
        $args=[
            'shop_id'=>1,
            'user_id'=>1
        ];
        $handler->store($args);
        $this->assertNotEquals(0, ShopUser::count());
    }

    public function testGetUsersShop()
    {
        $this->testStore();
        $handler = new ShopUserHandler();
        $shops = $handler->getUsersShop(1);
        $this->assertTrue($shops->where('shop_id',1)->count()>0);
    }

    public function testGetShopsUser()
    {
        $this->testStore();
        $handler = new ShopUserHandler();
        $users = $handler->getShopsUser(1);
        $this->assertTrue($users->where('user_id',1)->count()>0);
    }
}
