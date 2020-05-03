<?php

namespace Xpressengine\Plugins\XeroCommerce\Database\Seeds;

use Illuminate\Database\Seeder;
use Xpressengine\Plugins\XeroCommerce\Models\Carrier;

class CarrierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        self::storeDefaultCarrierSet();
    }

    public static function storeDefaultCarrierSet()
    {
        $deliery_list = [
            'cj대한통운' =>
                ['https://www.doortodoor.co.kr/parcel/doortodoor.do?fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no=',
                    Carrier::LOGIS],
            '한진택배' =>
                ['http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp?wbl_num=',
                    Carrier::LOGIS],
            '자체배송' =>
                ['',
                    Carrier::SELF],
            '수령' =>
                ['',
                    Carrier::TAKE],
        ];
        foreach ($deliery_list as $name => $option) {
            self::storeDefaultCarrier($name, $option[0], $option[1]);
        }
    }

    /**
     * @param $name
     * @param $uri
     * @param $type
     * @return void
     */
    public static function storeDefaultCarrier($name, $uri, $type)
    {
        $dc = new Carrier();
        $dc->name = $name;
        $dc->uri = $uri;
        $dc->type = $type;
        $dc->save();
    }
}

