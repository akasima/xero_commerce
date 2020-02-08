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

class OrderCheckExport implements FromCollection
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
                '이름'=>$item['user']['name'],
				'전화번호'=>$item['delivery']['recv_phone'],
                '메모'=>$item['name'],
				'우편번호'=> $item['delivery']['recv_addr'],
				'주소'=> $item['delivery']['recv_addr_detail'],
				'수량'=> $option['count']
            ];
        });

        $data->prepend([
            'id(고유키입니다. 수정하지마세요!)'=>'id(고유키입니다. 수정하지마세요!)',
            '이름'=>'이름',
            '전화번호'=>'전화번호',
            '메모'=>'메모',
            '우편번호'=>'우편번호',
			'주소'=> '주소',
			'수량'=>'수량'
        ]);
        return $data;
    }
}
