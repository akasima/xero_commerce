<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 23/04/2019
 * Time: 11:36 AM
 */

namespace Xpressengine\Plugins\XeroCommerce\ExcelImport;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Xpressengine\Plugins\XeroCommerce\Models\OrderItem;

class DeliveryImport implements ToCollection
{
    private $handler;
    /**
     * @param array $row
     *
     * @return Model|Model[]|null
     */
    public function __construct()
    {
        $this->handler = app('xero_commerce.orderHandler');
    }

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {

    }
}
