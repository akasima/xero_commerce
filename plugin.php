<?php

namespace Xpressengine\Plugins\XeroCommerce;

use Route;
use Xpressengine\Log\LogHandler;
use Xpressengine\Plugin\AbstractPlugin;
use Xpressengine\Plugins\XeroCommerce\Exceptions\XeroCommercePrefixUsedException;
use Xpressengine\Plugins\XeroCommerce\Logger\XeroCommerceLogger;
use Xpressengine\Plugins\XeroCommerce\Plugin\Database;
use Xpressengine\Plugins\XeroCommerce\Plugin\EventManager;
use Xpressengine\Plugins\XeroCommerce\Plugin\Resources;

class Plugin extends AbstractPlugin
{
    const XERO_COMMERCE_PREFIX = 'xero_commerce';

    const XERO_COMMERCE_URL_PREFIX = 'shopping';

    /**
     * 이 메소드는 활성화(activate) 된 플러그인이 부트될 때 항상 실행됩니다.
     *
     * @return void
     */
    public function boot()
    {
        self::registerXeroCommerceLogger();
        Resources::bindClasses();
        Resources::setCanNotUseXeroCommercePrefixRoute();
        Resources::setThumnailDimensionSEtting();
        Resources::registerRoute();
        \Xpressengine\XePlugin\XeroPay\Resources::registerRoute();
        \Xpressengine\XePlugin\XeroPay\Resources::registerMenu();
        Resources::registerSettingMenu();
        EventManager::listenEvents();
    }

    /**
     * @return void
     */
    private function registerXeroCommerceLogger()
    {
        app('xe.register')->push(LogHandler::PLUGIN_LOGGER_KEY, XeroCommerceLogger::ID, XeroCommerceLogger::class);
    }

    /**
     * 플러그인이 활성화될 때 실행할 코드를 여기에 작성한다.
     *
     * @param string|null $installedVersion 현재 XpressEngine에 설치된 플러그인의 버전정보
     *
     * @return void
     */
    public function activate($installedVersion = null)
    {
    }

    /**
     * 플러그인을 설치한다. 플러그인이 설치될 때 실행할 코드를 여기에 작성한다
     *
     * @return void
     */
    public function install()
    {
        if (Resources::isUsedXeroCommercePrefix() === true) {
            throw new XeroCommercePrefixUsedException;
        }

        Database::create();
        Resources::storeDefaultDeliveryCompanySet();
        Resources::storeAgreement('contacts', '주문자정보 수집 동의');
        Resources::storeAgreement('purchase', '구매 동의');
        Resources::storeAgreement('privacy', '개인정보 수집 및 이용동의');
        Resources::storeAgreement('thirdParty', '개인정보 제3자 제공/위탁동의');
        Resources::storeDefaultShop();
        Resources::setConfig();
        Resources::defaultSitemapSetting();
    }

    /**
     * 해당 플러그인이 설치된 상태라면 true, 설치되어있지 않다면 false를 반환한다.
     * 이 메소드를 구현하지 않았다면 기본적으로 설치된 상태(true)를 반환한다.
     *
     * @return boolean 플러그인의 설치 유무
     */
    public function checkInstalled()
    {
        return parent::checkInstalled();
    }

    /**
     * 플러그인을 업데이트한다.
     *
     * @return void
     */
    public function update()
    {

    }

    /**
     * 해당 플러그인이 최신 상태로 업데이트가 된 상태라면 true, 업데이트가 필요한 상태라면 false를 반환함.
     * 이 메소드를 구현하지 않았다면 기본적으로 최신업데이트 상태임(true)을 반환함.
     *
     * @return boolean 플러그인의 설치 유무,
     */
    public function checkUpdated()
    {
        $checkedUpdate = true;

        return $checkedUpdate;
    }
}
