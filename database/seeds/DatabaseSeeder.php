<?php

namespace Xpressengine\Plugins\XeroCommerce\Database\Seeds;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Seed는 unguard 상태로 실행되기 때문에, reguard를 통해 fillable 적용
        Model::reguard();

        // 재설치시에도 정상적으로 작동하도록 DB정리하는 Seeder
        $this->call(CleanerSeeder::class);

        // 설치시 실패하면 모든 변경사항을 되돌림
        \XeDB::beginTransaction();
        try {
            $this->call(DeliverySeeder::class);
            $this->call(ShopSeeder::class);
            $this->call(ConfigSeeder::class);
            $this->call(ProductSeeder::class);
            $this->call(SitemapSeeder::class);
        } catch (\Exception $e) {
            \XeDB::rollback();
            throw $e;
        }
        \XeDB::commit();
    }
}

