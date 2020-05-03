<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 22/04/2019
 * Time: 10:00 PM
 */

namespace Xpressengine\Plugins\XeroCommerce\ExcelExport;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class ShipmentExport implements FromCollection
{

    private $orderItems;

    public function __construct($orderItemollection)
    {
        $this->orderItems= $orderItemollection;
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        $data = $this->orderItems->map(function($item){
            $option = $item['options']->first();
            return [
                'id(고유키입니다. 수정하지마세요!)'=>$item['id'],
                '주문번호'=>$item['order_no'],
                '상품명'=>$item['name'].' '.$option['unit']['name'].' '.$option['count'].'개',
                '송장번호'=>'',
                '상태'=>$item['status']

            ];
        });

        $data->prepend([
            'id(고유키입니다. 수정하지마세요!)'=>'id(고유키입니다. 수정하지마세요!)',
            '주문번호'=>'주문번호',
            '상품명'=>'상품명',
            '송장번호'=>'송장번호',
            '상태'=>'상태'
        ]);
        return $data;
    }
}
