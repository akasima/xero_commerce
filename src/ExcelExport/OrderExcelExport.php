<?php
namespace Xpressengine\Plugins\XeroCommerce\ExcelExport;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Xpressengine\Plugins\XeroCommerce\Models\Order;
use function Aws\map;

class OrderExcelExport implements FromCollection {

    private $from_date;
    private $to_date;
    private $order;
    use Exportable;

    public function __construct($from_date, $to_date)
    {
        $this->from_date= $from_date;
        $this->to_date = $to_date;
        $this->order = Order::query();
    }
    public function query()
    {
//        Order::orderGroup()->whereBetween('created_at',[$this->from_date, $this->to_date]);

        $orders = $this->order->whereBetween('created_at',[$this->from_date, $this->to_date])->get()->map(function($orders){
            return $orders->items();
        });
        return $orders;
    }
    public function collection()
    {  $orders = $this->query();

        $data = $orders->map(function($orders){
            $orderItems = $orders->first();
            $order = $orderItems->order()->first();
            $userInfo = $order->userInfo()->first();
            return [
                'id'=>$order->id,
                '주문번호'=>$order->order_no,
                '주문자 id' =>$order->user_id,
                '주문자명' =>$userInfo->name,
                '주문일시'=>$order->created_at,
                '주문금액'=> $orderItems->original_price,
                '수량'=>$orderItems->count

            ];
        });

        $data->prepend([
            'id'=>'id',
            '주문번호'=>'주문번호',
            '주문자 id'=>'주문자 id',
            '주문자명'=>'주문자명',
            '주문일시'=>'주문일시',
            '주문금액'=>'주문금액',
            '수량'=>'수량'
        ]);
        return $data;
    }

}
