<?php

namespace Xpressengine\Plugins\XeroStore;

interface Order
{
    const TEMP = 0;
    const ORDERED = 1;
    const PAID = 2;
    const DELIVER = 3;
    const COMPLETE = 4;
    const CANCELING = 5;
    const CANCELED = 6;
    const EXCHANGING = 7;
    const EXCHANGED = 8;
    const REFUNDING = 9;
    const REFUNDED = 10;

    const STATUS = [
        '임시', '주문', '결제완료', '배송중', '배송완료','취소중', '취소완료', '교환중', '교환완료', '환불중', '환불완료'
    ];

    public function options();

    public function payment();

    public function save();

    public function getStatus();

    public function readyToOrder();
}
