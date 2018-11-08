<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'xero_commerce_payment';

    const ORDERED = 1;
    const PAID = 2;
    const DELIVER = 3;
    const COMPLETE = 4;

    const METHOD = [
        '없음', '무통장입금', '신용카드', '휴대폰', '문화상품권'
    ];

    public function getMethod()
    {
        if (is_null($this->method) || $this->method == '') {
            return '없음';
        }

        return $this->method;
    }
}
