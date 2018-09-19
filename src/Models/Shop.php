<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Xpressengine\Database\Eloquent\DynamicModel;

class Shop extends DynamicModel
{
    use SoftDeletes;

    const TYPE_BASIC_SHOP = 1;
    const TYPE_STORE = 2;
    const TYPE_INDIVIDUAL = 3;

    const APPROVAL_WAITING = 1;
    const APPROVAL_CONFIRM = 2;
    const APPROVAL_REJECT = 3;

    const BASIC_SHOP_NAME = 'basic_shop';

    protected $table = 'xero_commerce_shop';

    protected $fillable = ['shop_name', 'shop_eng_name', 'logo_path', 'background_path', 'shop_type', 'state_approval'];

    /**
     * @return array
     */
    public static function getShopTypes()
    {
        return [
            self::TYPE_STORE => '단체',
            self::TYPE_INDIVIDUAL => '개인'
        ];
    }

    /**
     * @return bool
     */
    public function isBasicShop()
    {
        if ($this->shop_type == self::TYPE_BASIC_SHOP) {
            return true;
        } else {
            return false;
        }
    }

    public function deliveryCompanys()
    {
        return $this->belongsToMany(
            DeliveryCompany::class,
            'xero_commerce_shop_delivery'
        )
            ->withPivot(
                ['delivery_fare', 'up_to_free']
            );
    }

    public function getDefaultDeliveryCompany()
    {
        return $this->deliveryCompanys()->where('is_default', 1)->first();
    }
}
