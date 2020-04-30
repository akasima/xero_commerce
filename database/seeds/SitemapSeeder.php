<?php

namespace Xpressengine\Plugins\XeroCommerce\Database\Seeds;

use App\Facades\XeConfig;
use App\Facades\XeDB;
use App\Facades\XeLang;
use App\Facades\XeMenu;
use Illuminate\Database\Seeder;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Document\ConfigHandler;
use Xpressengine\Menu\MenuHandler;
use Xpressengine\Permission\Grant;
use Xpressengine\Plugins\Banner\Widgets\Widget;
use Xpressengine\Plugins\XeroCommerce\Components\Widget\DefaultWidget\Skins\Common\CommonSkin as DefaultWidgetCommonSkin;
use Xpressengine\Plugins\XeroCommerce\Components\Widget\MainSlider\MainSliderSkin;
use Xpressengine\Plugins\XeroCommerce\Components\Widget\RecommendSlider\RecommendSliderSkin;
use Xpressengine\Plugins\XeroCommerce\Plugin;

class SitemapSeeder extends Seeder
{

    const MENU_TITLE = 'XeroCommerce';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Plugin\Resources::setCanUseXeroCommercePrefixRoute();

        // XeroCommerce 이름의 메뉴가 있는지 확인후, 있으면 생성 skip
        $defaultMenu = XeMenu::menus()->query()->where('title', self::MENU_TITLE)->first();
        if($defaultMenu) {
            return;
        }
        $defaultMenu = self::createDefaultMenu(self::MENU_TITLE, 'XeroCommerce 메뉴 입니다.');
        ConfigSeeder::storeConfigData('mainMenuId', $defaultMenu['id']);

        $mainWidgetboxPageId = self::createDefaultMainWidgetboxPage($defaultMenu);
        ConfigSeeder::storeConfigData('mainPageId', $mainWidgetboxPageId);

        self::createDefaultCategoryModule($defaultMenu);
        self::setDefaultThemeConfig($defaultMenu);
        self::createXeroCommerceDirectLink();
        self::settingWidget($mainWidgetboxPageId);

        $noticeBoardId = self::createNoticeBoard($defaultMenu);
        ConfigSeeder::storeConfigData('noticeBoardId', $noticeBoardId);
        self::storeDefaultNotice($noticeBoardId);

        Plugin\Resources::setCanNotUseXeroCommercePrefixRoute();
    }

    protected static function createDefaultMenu($menuTitle, $menuDescription)
    {
        //TODO 테마 자동 설정 필요
        XeConfig::set('theme.settings.theme/xero_commerce@xero_commerce_theme_default', []);
        XeConfig::set('theme.settings.theme/xero_commerce@xero_commerce_theme_default.0', ['layout_type' => 'sub']);
        $desktopTheme = 'theme/xero_commerce@xero_commerce_theme_default.0';
        $mobileTheme = 'theme/xero_commerce@xero_commerce_theme_default.0';

        $menu = XeMenu::createMenu([
            'title' => $menuTitle,
            'description' => $menuDescription,
            'site_key' => \XeSite::getCurrentSiteKey(),
        ]);

        XeMenu::setMenuTheme($menu, $desktopTheme, $mobileTheme);

        app('xe.permission')->register($menu->getKey(), XeMenu::getDefaultGrant());

        return $menu;
    }

    protected static function createDefaultMainWidgetboxPage($defaultMenu)
    {
        //TODO 리펙토링
        $inputs['parent'] = $defaultMenu['id'];
        $inputs['siteKey'] = $defaultMenu['siteKey'];
        $inputs['itemTitle'] = self::getTranslationKey('MainPage');
        $inputs['itemUrl'] = Plugin::XERO_COMMERCE_MAIN_PAGE_URL;
        $inputs['itemDescription'] = '메인 페이지에 출력될 위젯을 설정할 수 있습니다.';
        $inputs['itemTarget'] = '_self';
        $inputs['selectedType'] = 'widgetpage@widgetpage';
        $inputs['itemOrdering'] = 0;
        $inputs['itemActivated'] = 1;

        $itemInputKeys = [
            'itemId',
            'parent',
            'itemTitle',
            'itemUrl',
            'itemDescription',
            'itemTarget',
            'selectedType',
            'itemOrdering',
            'itemActivated',
            'basicImage',
            'hoverImage',
            'selectedImage',
        ];

        $itemInput = array_only($inputs, $itemInputKeys);
        $menuTypeInput = array_except($inputs, $itemInputKeys);

        \XeConfig::set(
            'theme.settings.theme/xero_commerce@xero_commerce_theme_default.1',
            ['layout_type' => 'main', 'gnb_sub' => $defaultMenu['id']]
        );
        $desktopTheme = 'theme/xero_commerce@xero_commerce_theme_default.1';
        $mobileTheme = null;

        $itemInput['parent'] = $itemInput['parent'] === $defaultMenu->getKey() ? null : $itemInput['parent'];
        $item = XeMenu::createItem($defaultMenu, [
            'title' => $itemInput['itemTitle'],
            'url' => trim($itemInput['itemUrl'], " \t\n\r\0\x0B/"),
            'description' => $itemInput['itemDescription'],
            'target' => $itemInput['itemTarget'],
            'type' => $itemInput['selectedType'],
            'ordering' => $itemInput['itemOrdering'],
            'activated' => isset($itemInput['itemActivated']) ? $itemInput['itemActivated'] : 0,
            'parent_id' => $itemInput['parent']
        ], $menuTypeInput);

        XeMenu::setMenuItemTheme($item, $desktopTheme, $mobileTheme);
        app('xe.permission')->register(XeMenu::permKeyString($item), new Grant, $defaultMenu->site_key);

        return $item->id;
    }

    protected static function createDefaultCategoryModule($defaultMenu)
    {
        $config = \XeConfig::get(Plugin::getId());
        $categoryId = $config->get('categoryId', '');

        if ($categoryId === '') {
            return;
        }

        $itemInputKeys = [
            'itemId',
            'parent',
            'itemTitle',
            'itemUrl',
            'itemDescription',
            'itemTarget',
            'selectedType',
            'itemOrdering',
            'itemActivated',
            'basicImage',
            'hoverImage',
            'selectedImage',
        ];

        $initCategories = CategoryItem::where('category_id', $categoryId)->get();
        foreach ($initCategories as $idx => $category) {
            $inputs = [];
            $inputs['parent'] = $defaultMenu['id'];
            $inputs['siteKey'] = $defaultMenu['siteKey'];
            $inputs['itemTitle'] = self::getTranslationKey(xe_trans($category['word']));
            $inputs['itemUrl'] = 'category' . ($idx + 1);
            $inputs['itemDescription'] = '기본 상품 페이지입니다.';
            $inputs['itemTarget'] = '_self';
            $inputs['selectedType'] = 'xero_commerce@xero_commerce_module';
            $inputs['itemOrdering'] = 0;
            $inputs['itemActivated'] = 1;
            $inputs['categoryItemId'] = $category['id'];
            $inputs['categoryItemDepth'] = 1;

            $itemInput = array_only($inputs, $itemInputKeys);
            $menuTypeInput = array_except($inputs, $itemInputKeys);

            $desktopTheme = null;
            $mobileTheme = null;

            $itemInput['parent'] = $itemInput['parent'] === $defaultMenu->getKey() ? null : $itemInput['parent'];
            $item = XeMenu::createItem($defaultMenu, [
                'title' => $itemInput['itemTitle'],
                'url' => trim($itemInput['itemUrl'], " \t\n\r\0\x0B/"),
                'description' => $itemInput['itemDescription'],
                'target' => $itemInput['itemTarget'],
                'type' => $itemInput['selectedType'],
                'ordering' => $itemInput['itemOrdering'],
                'activated' => isset($itemInput['itemActivated']) ? $itemInput['itemActivated'] : 0,
                'parent_id' => $itemInput['parent']
            ], $menuTypeInput);

            XeMenu::setMenuItemTheme($item, $desktopTheme, $mobileTheme);
            app('xe.permission')->register(XeMenu::permKeyString($item), new Grant, $defaultMenu->site_key);

        }
    }

    public static function setDefaultThemeConfig($defaultMenu)
    {
        $config['logo_title'] = 'XeroCommerce';
        $config['gnb_sub'] = $defaultMenu['id'];

        app('xe.theme')->setThemeConfig('theme/xero_commerce@xero_commerce_theme_default.0', $config);
    }

    protected static function createXeroCommerceDirectLink()
    {
        $menuId = \XeConfig::get('site.default')['defaultMenu'];
        $defaultMenu = XeMenu::menus()->find($menuId);

        //TODO 리펙토링
        $inputs['parent'] = $menuId;
        $inputs['siteKey'] = $defaultMenu['siteKey'];
        $inputs['itemTitle'] = self::getTranslationKey('XeroCommerce');
        $inputs['itemUrl'] = Plugin::XERO_COMMERCE_MAIN_PAGE_URL;
        $inputs['itemDescription'] = '메인 페이지에 추가된 쇼핑몰 링크입니다.';
        $inputs['itemTarget'] = '_self';
        $inputs['selectedType'] = 'xpressengine@directLink';
        $inputs['itemOrdering'] = 0;
        $inputs['itemActivated'] = 1;

        $itemInputKeys = [
            'itemId',
            'parent',
            'itemTitle',
            'itemUrl',
            'itemDescription',
            'itemTarget',
            'selectedType',
            'itemOrdering',
            'itemActivated',
            'basicImage',
            'hoverImage',
            'selectedImage',
        ];

        $itemInput = array_only($inputs, $itemInputKeys);
        $menuTypeInput = array_except($inputs, $itemInputKeys);

            $desktopTheme = null;
            $mobileTheme = null;

            $itemInput['parent'] = $itemInput['parent'] === $defaultMenu->getKey() ? null : $itemInput['parent'];
            $item = XeMenu::createItem($defaultMenu, [
                'title' => $itemInput['itemTitle'],
                'url' => trim($itemInput['itemUrl'], " \t\n\r\0\x0B/"),
                'description' => $itemInput['itemDescription'],
                'target' => $itemInput['itemTarget'],
                'type' => $itemInput['selectedType'],
                'ordering' => $itemInput['itemOrdering'],
                'activated' => isset($itemInput['itemActivated']) ? $itemInput['itemActivated'] : 0,
                'parent_id' => $itemInput['parent']
            ], $menuTypeInput);

            XeMenu::setMenuItemTheme($item, $desktopTheme, $mobileTheme);
            app('xe.permission')->register(XeMenu::permKeyString($item), new Grant, $defaultMenu->site_key);

    }

    private static function getTranslationKey($title)
    {
        $key = Plugin::XERO_COMMERCE_PREFIX . '::' . app('xe.keygen')->generate();

        XeLang::save($key, 'ko', $title, false);

        return $key;
    }

    protected static function settingWidget($mainWidgetboxPageId)
    {
        $widgetboxHandler = app('xe.widgetbox');

        $widgetboxPrefix = 'widgetpage-';
        $id = $widgetboxPrefix . $mainWidgetboxPageId;

        $categoryId = \XeConfig::get(Plugin::getId())->get('categoryId', '');
        $initCategories = CategoryItem::where('category_id', $categoryId)->pluck('id')->toArray();

        if (empty($initCategories) == false) {
            $initCategories = implode(',', $initCategories);
        }

        //Default Widget
        $sample = file_get_contents(DefaultWidgetCommonSkin::getPath().'/assets/img/tmp_spot.jpg');

        $firstFile = \XeStorage::create($sample, 'public/xero_commerce/widget/default', 'default1.jpg');
        $firstImageFile = \XeMedia::make($firstFile);


        //Banner Widget

        $arr = [
            'title' => 'XeroCommerce배너',
            'skin' => MainSliderSkin::getId()
        ];
        $bannerGroup = app('xe.banner')->createGroup($arr);
        $img = [
            'id' => $firstFile->id,
            'filename' => $firstFile->clientname,
            'path' => $firstImageFile->url()
        ];
        app('xe.banner')->createItem($bannerGroup, [
            'title' => 'XeroCommerce',
            'content' => 'XE3가 제공하는 당신이 원하는 쇼핑몰.',
            'image' => $img,
            'status' => 'show'
        ]);

        $bannerWidget['group_id'] = $bannerGroup->id;
        $bannerWidget['@attributes'] = [
            'id' => Widget::getId(),
            'title' => '메인 슬라이더 배너 위젯',
            'skin-id' => MainSliderSkin::getId()
        ];

        //Label Widget
        $labelWidget['label_id'] = '1';
        $labelWidget['category_item_id'] = $initCategories;
        $labelWidget['product_id'] = '1,2,3,5,6,7,9,10,11';
        $labelWidget['@attributes'] = [
            'id' => 'widget/xero_commerce@label_product_widget',
            'title' => 'Label',
            'skin-id' => 'widget/xero_commerce@label_product_widget/skin/xero_commerce@label_widget_common_skin'
        ];

        //sedcondBannerWidget

        $secondArr = [
            'title' => 'XeroCommerce추천',
            'skin' => RecommendSliderSkin::getId()
        ];
        $secondBannerGroup = app('xe.banner')->createGroup($secondArr);
        $img = [
            'id' => $firstFile->id,
            'filename' => $firstFile->clientname,
            'path' => $firstImageFile->url()
        ];
        app('xe.banner')->createItem($secondBannerGroup, [
            'title' => 'XeroCommerce추천상품',
            'content' => 'MD가 직접 선별할 수 있는 추천상품목록',
            'image' => $img,
            'status' => 'show'
        ]);

        $secondBannerWidget['group_id'] = $secondBannerGroup->id;
        $secondBannerWidget['@attributes'] = [
            'id' => Widget::getId(),
            'title' => 'MD 추천 상품',
            'skin-id' => MainSliderSkin::getId()
        ];

        //Event Widget
        $eventWidget['product_id_1'] = '1';
        $eventWidget['product_id_2'] = '4';
        $eventWidget['product_id_3'] = '8';
        $eventWidget['product_id_4'] = '12';
        $eventWidget['product_id_5'] = '1';
        $eventWidget['@attributes'] = [
            'id' => 'widget/xero_commerce@event_widget',
            'title' => 'Event',
            'skin-id' => 'widget/xero_commerce@event_widget/skin/xero_commerce@event_widget_common_skin'
        ];

        //ProductListWidget
        $productWidget['@attributes'] = [
            'id' => 'widget/xero_commerce@product_list_widget',
            'title' => '추천상품목록',
            'skin-id' => 'widget/xero_commerce@product_list_widget/skin/xero_commerce@product_list_widget_common_skin'
        ];

        $initValue['grid'] = ['md' => '12'];
        $initValue['rows'] = [];
        $initValue['widgets'] = [$bannerWidget, $labelWidget, $secondBannerWidget, $eventWidget, $productWidget];

        $value[] = $initValue;

        $widgetboxHandler->update($id, ['content' => [$value]]);
    }


    protected static function createNoticeBoard($defaultMenu)
    {
        /** @var MenuHandler $menuHandler */
        $menuHandler = app('xe.menu');

        $menuTitle = XeLang::genUserKey();
        foreach (XeLang::getLocales() as $locale) {
            $value = "공지사항";
            if ($locale != 'ko') {
                $value = "Notice";
            }
            XeLang::save($menuTitle, $locale, $value, false);
        }

        $inputs = [
            'menu_id' => $defaultMenu->id,
            'parent_id' => null,
            'title' => $menuTitle,
            'url' => 'xero_commerce_notice',
            'description' => '공지사항',
            'target' => '',
            'type' => 'board@board',
            'ordering' => '1',
            'activated' => '1',
        ];
        $menuTypeInput = [
            'page_title' => 'XeroCommerce Notice Board',
            'board_name' => 'Notice',
            'site_key' => 'default',
            'revision' => 'true',
            'division' => 'false',
        ];

        $item = $menuHandler->createItem($defaultMenu, $inputs, $menuTypeInput);

        $menuHandler->setMenuItemTheme($item, null, null);
        app('xe.permission')->register($menuHandler->permKeyString($item), new Grant);

        return $item->id;
    }

    protected static function storeDefaultNotice($noticeBoardInstanceId)
    {
        /** @var \Xpressengine\Plugins\Board\Handler $boardHandler */
        $boardHandler = app('xe.board.handler');

        /** @var ConfigHandler $configHandler */
        $configHandler = app('xe.board.config');
        $config = $configHandler->get($noticeBoardInstanceId);

        $user = \Auth::user();

        $inputs['head'] = null;
        $inputs['queryString'] = null;
        $inputs['title'] = 'XeroCommerce 공지사항';
        $inputs['slug'] = 'XeroCommerce 공지사항';
        $inputs['content'] = '<p>XeroCommerce 기본 공지사항입니다.</p>';
        $inputs['_coverId'] = null;
        $inputs['allow_comment'] = '1';
        $inputs['use_alarm'] = '1';
        $inputs['file'] = null;
        $inputs['instance_id'] = $noticeBoardInstanceId;
        $inputs['format'] = 10;
        $inputs['_files'] = [];
        $inputs['_hashTags'] = [];

        $boardHandler->add($inputs, $user, $config);
    }

}

