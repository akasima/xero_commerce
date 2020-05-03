<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 11/12/2018
 * Time: 2:26 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Migrations\UserMigration;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;
use PHPUnit\Framework\TestCase;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;

class ShopHandlerTest extends DefaultSet
{


    public function testStore()
    {
        $handler=  new ShopHandler();
        $args = [
            'shop_name'=>'test',
            'shop_eng_name'=>'test',
            'shop_type'=>Shop::TYPE_INDIVIDUAL,
            'state_approval'=>Shop::APPROVAL_WAITING,
            'shipping_info'=>'',
            'as_info'=>'',
            'user_id'=>Auth::id()
        ];
        $handler->store($args);
        $this->assertNotEquals(0, Shop::count());
    }

    public function testGetShopsQuery()
    {
        $handler=  new ShopHandler();
        $query = $handler->getShopsQuery(
            [
                'user_id'=>'test',
                'shop_name'=>'test'
            ]
        );
        $this->assertInstanceOf(Builder::class, $query);
    }

    public function testGetShop()
    {
        $handler = new ShopHandler();
        $this->testStore();
        $shop = Shop::first();
        $return = $handler->getShop($shop->id);
        $this->assertNotNull($return);
    }

    public function testUpdate()
    {

        $handler = new ShopHandler();
        $this->testStore();
        $shop = Shop::first();
        $handler->update([
            'shop_name'=>'updateTest'
        ], $shop);
        $this->assertEquals('updateTest', $shop->shop_name);
    }

    public function testDestroy()
    {


        $handler = new ShopHandler();
        $this->testStore();
        $shop = Shop::first();
        $handler->destroy($shop->id);
        $this->assertEquals(0, Shop::count());
    }
}
