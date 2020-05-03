<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Xpressengine\Database\Eloquent\DynamicModel;
use Xpressengine\User\Models\User;

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

    protected $table = 'xero_commerce__shops';

    protected $fillable = ['shop_name', 'shop_eng_name', 'logo_path', 'background_path', 'shop_type',
        'state_approval', 'shipping_info', 'as_info'];

    /**
     * @return array
     */
    public static function getShopTypes()
    {
        return [
            self::TYPE_BASIC_SHOP => '기본',
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

    public function carriers()
    {
        return $this->belongsToMany(
            Carrier::class,
            (new ShopCarrier)->getTable()
        )->withPivot(
            ['id', 'fare', 'up_to_free', 'addr', 'addr_detail', 'addr_post']
        );
    }

    public function getDefaultCarrier()
    {
        $default = $this->carriers()->where('is_default', 1)->first();
        return $default?:$this->carriers()->first();
    }

    public function users()
    {
        return $this->belongsToMany(User::class, (new ShopUser)->getTable());
    }

    public function logo()
    {
        return $this->belongsTo(\Xpressengine\Media\Models\Image::class, 'logo_path');
    }
}
