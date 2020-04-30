<?php

namespace Xpressengine\Plugins\XeroCommerce\Database\Seeds;

use Illuminate\Database\Seeder;
use Xpressengine\Plugins\XeroCommerce\Models\DeliveryCompany;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::storeDefaultDeliveryCompanySet();
    }

    public static function storeDefaultDeliveryCompanySet()
    {
        $deliery_list = [
            'cj대한통운' =>
                ['https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=',
                    DeliveryCompany::LOGIS],
            '한진택배' =>
                ['http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=',
                    DeliveryCompany::LOGIS],
            '자체배송' =>
                ['',
                    DeliveryCompany::SELF],
            '수령' =>
                ['',
                    DeliveryCompany::TAKE],
        ];
        foreach ($deliery_list as $name => $option) {
            self::storeDefaultDeliveryCompany($name, $option[0], $option[1]);
        }
    }

    /**
     * @param $name
     * @param $uri
     * @param $type
     * @return void
     */
    public static function storeDefaultDeliveryCompany($name, $uri, $type)
    {
        $dc = new DeliveryCompany();
        $dc->name = $name;
        $dc->uri = $uri;
        $dc->type = $type;
        $dc->save();
    }
}

