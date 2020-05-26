<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 10/12/2018
 * Time: 10:58 AM
 */
namespace Xpressengine\Plugins\XeroCommerce\Test\Unit;

use App\Http\Kernel;
use App\Providers\CategoryServiceProvider;
use App\Providers\ConfigServiceProvider;
use App\Providers\DatabaseServiceProvider;
use App\Providers\EventServiceProvider;
use App\Providers\InterceptionServiceProvider;
use Composer\EventDispatcher\Event;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use Mockery as m;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Xpressengine\Config\ConfigManager;
use Xpressengine\Config\ConfigRepository;
use Xpressengine\Config\Repositories\CacheDecorator;
use Xpressengine\Config\Repositories\DatabaseRepository;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\Database\ProxyManager;
use Xpressengine\Database\VirtualConnection;
use Xpressengine\Keygen\Keygen;
use Xpressengine\Migrations\ConfigMigration;
use Xpressengine\Plugins\XeroCommerce\Database\Seeds\CarrierSeeder;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Carrier;
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Plugin\EventManager;
use Xpressengine\Plugins\XeroCommerce\Plugin\Resources;
use Xpressengine\User\Rating;

class DefaultSet extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $app = new \Illuminate\Container\Container();
        $app->singleton('app', 'Illuminate\Container\Container');

        $app->singleton('config', 'Illuminate\Config\Repository');
//
        $app['config']->set('database.default', 'test');
        $app['config']->set('database.connections.test', [
            'driver'   => 'sqlite',
            'database' => __DIR__.'/test.db'
        ]);
        $app['config']->set('auth.defaults.guard', 'test');
        $app['config']->set('auth.guards.test',[
            'driver' => 'test',
            'provider' => 'users',
        ]);

        $app->bind('db', function ($app) {
            return new \Illuminate\Database\DatabaseManager($app, new \Illuminate\Database\Connectors\ConnectionFactory($app));
        });

        $app->bind('auth',function ($app) {
            $authmanager = new \Illuminate\Auth\AuthManager($app);
            $authmanager->extend('test', function ($app, $name, $config) {
                $guard = m::mock(\Illuminate\Contracts\Auth\Guard::class);
                $guard->shouldReceive('id')->andReturn(1);
                $guard->shouldReceive('check')->andReturn(true);
                $guard->shouldReceive('user')->andReturn((object)['rating'=>Rating::USER]);
                return $guard;
            });
            return $authmanager;
        });


        $app->singleton(
            \Illuminate\Contracts\Http\Kernel::class,
            Kernel::class
        );

        $app->singleton(
            Illuminate\Contracts\Console\Kernel::class,
            App\Console\Kernel::class
        );

        DynamicModel::setConnectionResolver($app['db']);
        Model::setConnectionResolver($app['db']);
        DynamicModel::setKeyGen(new Keygen());

        \Illuminate\Support\Facades\Facade::setFacadeApplication($app);
        // \Xpressengine\Plugins\XeroCommerce\Plugin\Database::create();
    }

    public function tearDown()
    {
        parent::tearDown();
        // \Xpressengine\Plugins\XeroCommerce\Plugin\Database::drop();
    }

    protected function makeProduct()
    {
        $product = new Product();
        $product->shop_id = 1;
        $product->product_code = 'test';
        $product->detail_info = [
            '상품정보' => '샘플 상품',
            '비고' => '수정해서 사용'
        ];
        $product->name = '지금부터 봄까지 입는 데일리 인기신상 ITEM';
        $product->sub_name = '간단한 상품설명';
        $product->original_price = 1000;
        $product->sell_price = $product->original_price;
        $product->discount_percentage = 100;
        $product->description = '상품설명페이지';
        $product->tax_type = rand(Product::TAX_TYPE_TAX, Product::TAX_TYPE_FREE);
        $product->state_display = Product::DISPLAY_VISIBLE;
        $product->state_deal = Product::DEAL_ON_SALE;
        $product->shop_carrier_id = 1;
        $product->save();
        Resources::storeProductOption($product->id);
        return $product;
    }

    protected function makeShop()
    {
        $args['user_id'] = 'test';
        $args['shop_type'] = Shop::TYPE_STORE;
        $args['shop_name'] = Shop::BASIC_SHOP_NAME;
        $newShop = new Shop();

        $newShop->fill($args);

        $newShop->save();

        CarrierSeeder::storeDefaultCarrierSet();

        $newShop->carriers()->attach(Carrier::pluck('id'), [
            'fare'=>0,
            'up_to_free'=>0,
            'is_default'=>0
        ]);
    }
}
