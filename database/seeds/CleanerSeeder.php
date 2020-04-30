<?php

namespace Xpressengine\Plugins\XeroCommerce\Database\Seeds;

use Illuminate\Database\Seeder;
use Xpressengine\Menu\Models\Menu;
use Xpressengine\Menu\Models\MenuItem;
use Xpressengine\Plugins\XeroCommerce\Components\Widget\MainSlider\MainSliderSkin;
use Xpressengine\Plugins\XeroCommerce\Components\Widget\RecommendSlider\RecommendSliderSkin;
use Xpressengine\Plugins\XeroCommerce\Models\DeliveryCompany;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class CleanerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 기본 사이트메뉴 삭제
        $this->deleteExistingMenus();
        // 카테고리 삭제
        $this->deleteCategories();
        // 배너삭제
        $this->deleteBanners();
    }

    private function deleteExistingMenus()
    {
        $deleteItemLoop = function(MenuItem $item) use (&$deleteItemLoop) {
            if($item->hasChild()) {
                $item->descendants->map(function($child) use (&$deleteItemLoop) {
                    $deleteItemLoop($child);
                });
            }
            \XeMenu::deleteItem($item);
        };

        // 이름이 XeroCommerce 기본값인 메뉴들을 가져와서
        $existingMenus = \XeMenu::menus()->query()->where('title', SitemapSeeder::MENU_TITLE)->get();
        $existingMenus->map(function(Menu $menu) use ($deleteItemLoop) {
            // 하위 아이템까지 전부 삭제
            $menu->getProgenitors()->map($deleteItemLoop);

            \XeMenu::deleteMenu($menu);
        });

        // directLink도 삭제
        if($mainPageItem = \XeMenu::items()->query()->where('url', Plugin::XERO_COMMERCE_MAIN_PAGE_URL)->first()) {
            $deleteItemLoop($mainPageItem);
        }

        // categoryLink도 삭제
        $categoryItems = \XeMenu::items()->query()->where('url', 'LIKE', 'category%')->get();
        if($categoryItems->count()) {
            $categoryItems->map($deleteItemLoop);
        }

        // 홈페이지가 사라졌다면 (XeroCommerce페이지를 기본으로 해놓았었다면), 에러를 방지하기 위해 다른 페이지로 변경
        $siteConfig = \XeConfig::get('site.default');
        $homeInstance = \XeMenu::items()->query()->find($siteConfig['homeInstance']);
        if(!$homeInstance) {
            // 대체 메뉴를 가져옴 (가장 첫번째 페이지)
            $alternativeInstance = \XeMenu::items()->query()->orderBy('ordering')->first();

            // 설정 덮어쓰기
            \XeConfig::set('site.default', ['homeInstance' => $alternativeInstance->id]);
        }

    }

    private function deleteCategories()
    {
        \XeCategory::cates()->query()->where('name', '상품 분류')->get()
            ->map(function($cate) {
                \XeCategory::deleteCate($cate);
            });
    }

    private function deleteBanners()
    {
        // 메인배너 제거
        app('xe.banner')->getGroupsBySkin(MainSliderSkin::getId())
            ->map(function($group) {
                app('xe.banner')->removeGroup($group);
            });
        // 추천배너 제거
        app('xe.banner')->getGroupsBySkin(RecommendSliderSkin::getId())
            ->map(function($group) {
                app('xe.banner')->removeGroup($group);
            });
    }
}

