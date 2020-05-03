<?php

namespace Xpressengine\Plugins\XeroCommerce\Database\Seeds;

use App\Facades\XeConfig;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Xpressengine\Plugins\CkEditor\Editors\CkEditor;
use Xpressengine\Plugins\XeroCommerce\Handlers\ShopHandler;
use Xpressengine\Plugins\XeroCommerce\Models\Agreement;
use Xpressengine\Plugins\XeroCommerce\Models\Carrier;
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Plugin;
use Xpressengine\Plugins\XeroCommerce\Plugin\Resources;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        XeConfig::set('xero_pay', [
            'uses' => 'xero_pay/xero_commerce@account',
            'pg' => [
                'xero_pay/xero_commerce@account' => [
                    'Msg' => 'input Description',
                    'AccountNo' => 'input your account number',
                    'Host' => 'input your account owner name',
                ],
            ],
        ]);
        \XeEditor::setInstance(Plugin::getId(), CkEditor::getId());
        \XeEditor::setConfig(Plugin::getId(), ['uploadActive' => true]);
    }

    /**
     * @param string $configKey configKey
     * @param string $configValue configValue
     *
     * @return void
     */
    public static function storeConfigData($configKey, $configValue)
    {
        $config = \XeConfig::get(Plugin::getId());
        if ($config === null) {
            \XeConfig::add(Plugin::getId(), [$configKey => $configValue]);
        } else {
            $config->set($configKey, $configValue);
            \XeConfig::modify($config);
        }
    }

}

