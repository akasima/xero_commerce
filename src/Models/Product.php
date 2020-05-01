<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use App\Facades\XeMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Xpressengine\Category\Models\Category;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Plugins\XeroCommerce\Handlers\ProductCategoryHandler;
use Xpressengine\Plugins\XeroCommerce\Services\ProductCategoryService;
use Xpressengine\Plugins\XeroCommerce\Traits\CustomTableInheritanceTrait;
use Xpressengine\Tag\Tag;
use Xpressengine\Plugins\XeroCommerce\Models\Products\DigitalProduct;
use Xpressengine\Plugins\XeroCommerce\Models\Products\TimeProduct;

class Product extends SellType
{
    use SoftDeletes, CustomTableInheritanceTrait;

    const IMG_MAXSIZE = 50000000;

    const DISPLAY_VISIBLE = 1;
    const DISPLAY_HIDDEN = 2;

    const DEAL_ON_SALE = 1;
    const DEAL_PAUSE = 2;
    const DEAL_END = 3;

    const TAX_TYPE_TAX = 1;
    const TAX_TYPE_NO = 2;
    const TAX_TYPE_FREE = 3;

    const OPTION_TYPE_COMBINATION_MERGE = 0;  // 조합 일체 선택형
    const OPTION_TYPE_COMBINATION_SPLIT = 1;  // 조합 분리 선택형
    const OPTION_TYPE_SIMPLE = 2;  // 단독형

    protected $table = 'xero_commerce__products';

    protected $fillable = ['shop_id', 'type', 'product_code', 'name', 'original_price', 'sell_price', 'discount_percentage',
        'min_buy_count', 'max_buy_count', 'description', 'badge_id', 'tax_type', 'option_type', 'state_display',
        'state_deal', 'sub_name', 'shop_delivery_id'];

    protected static $singleTableTypeField = 'type';

    public static $singleTableType = 'general';

    public static $singleTableName = '일반 상품';

    protected static $singleTableSubclasses = [DigitalProduct::class, TimeProduct::class];

    /**
     * @return array
     */
    public static function getDisplayStates()
    {
        return [
            self::DISPLAY_VISIBLE => '출력',
            self::DISPLAY_HIDDEN => '숨김'
        ];
    }

    /**
     * @return array
     */
    public static function getDealStates()
    {
        return [
            self::DEAL_ON_SALE => '판매중',
            self::DEAL_PAUSE => '판매 일시 중지',
            self::DEAL_END => '거래 종료',
        ];
    }

    /**
     * @return array
     */
    public static function getTaxTypes()
    {
        return [
            self::TAX_TYPE_TAX => '과세',
            self::TAX_TYPE_NO => '비과세',
            self::TAX_TYPE_FREE => '면세',
        ];
    }

    /**
     * @return string
     */
    public function getTaxTypeName()
    {
        $taxType = self::getTaxTypes();

        return $taxType[$this->tax_type];
    }

    /**
     * @return array
     */
    public static function getOptionTypes()
    {
        return [
            self::OPTION_TYPE_COMBINATION_MERGE => '조합 일체선택형',
            self::OPTION_TYPE_COMBINATION_SPLIT => '조합 분리선택형',
            self::OPTION_TYPE_SIMPLE => '단독형',
        ];
    }

    /**
     * @return string
     */
    public function getOptionTypeName()
    {
        $optionTypes = self::getOptionTypes();

        return $optionTypes[$this->option_type];
    }

    public function getAvailableOptions()
    {
        // TODO : optionItems에 있는 옵션들만 출력되도록 구현필요
        return $this->options;
    }

    public function getAvailableCustomOptions()
    {
        return $this->customOptions;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(ProductOption::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function optionItems()
    {
        return $this->hasMany(ProductOptionItem::class, 'product_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customOptions()
    {
        return $this->hasMany(ProductCustomOption::class, 'product_id', 'id');
    }

    function getJsonFormat()
    {
        $array = parent::getJsonFormat(); // TODO: Change the autogenerated stub
        $array['labels'] = $this->labels;
        $array['badge'] = $this->badge;
        $array['categorys'] = $this->getCategorys();
        return $array;
    }

    function getCategorys()
    {
        $productCategoryService = new ProductCategoryService();
        return $productCategoryService->getProductCategoryTree($this->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getInfo()
    {
        return $this->sub_name;
    }

    public function getFare()
    {
        return $this->getDelivery()->delivery_fare;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getShop()
    {
        return $this->shop;
    }

    public function getThumbnailSrc($size = 'M')
    {
        $url = 'https://via.placeholder.com/150x120';
        $imageItem = $this->images->first();
        if ($imageItem == null) {
            return $url;
        }
        return XeMedia::images()->getThumbnail($imageItem, 'widen', $size)->url();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sellUnits()
    {
        return $this->hasMany(ProductOptionItem::class, 'product_id');
    }

    /**
     * @return callable
     */
    public function getCountMethod()
    {
        return function ($sellGroupCollection) {
            return $sellGroupCollection->sum(function (SellGroup $sellGroup) {
                return $sellGroup->getCount();
            });
        };
    }

    /**
     * @return callable
     */
    public function getOriginalPriceMethod()
    {
        return function ($sellGroupCollection) {
            return $sellGroupCollection->sum(function (SellGroup $sellGroup) {
                return $sellGroup->getOriginalPrice();
            });
        };
    }

    /**
     * @return callable
     */
    public function getSellPriceMethod()
    {
        return function ($sellGroupCollection) {
            return $sellGroupCollection->sum(function (SellGroup $sellGroup) {
                return $sellGroup->getSellPrice();
            });
        };
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'taggables', 'taggable_id', 'tag_id');
    }

    public function getSlug()
    {
        return $this->slug->slug;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function slug()
    {
        return $this->belongsTo(ProductSlug::class, 'id', 'target_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productSlug()
    {
        return $this->hasOne(ProductSlug::class, 'target_id');
    }

    public function labels()
    {
        return $this->hasManyThrough(Label::class, ProductLabel::class, 'product_id', 'id', 'id', 'label_id');
    }

    public function badge()
    {
        return $this->hasOne(Badge::class, 'id', 'badge_id');
    }

    function getContents()
    {
        return '';
    }

    public function getStock()
    {
        return $this->sellUnits()->sum('stock');
    }

    public function category()
    {
        return $this->hasManyThrough(CategoryItem::class, ProductCategory::class, 'product_id', 'id','id','category_id');
    }

    public function qna()
    {
        return $this->morphMany(Qna::class,'type');
    }

    public function feedback()
    {
        return $this->morphMany(FeedBack::class,'type');
    }

    public function slugUrl()
    {
        return route('xero_commerce::product.show', ['strSlug' => $this->getSlug()]);
    }

    function renderForSellSet(SellSet $sellSet)
    {
        $row = [];
        $row [] = '<a target="_blank' . now()->toTimeString() . '" href="' . route('xero_commerce::product.show', ['strSlug' => $this->getSlug()]) . '">' . $sellSet->renderSpanBr($this->getName()) . '</a>';
        $row [] = $sellSet->renderSpanBr($this->getInfo());
        $sellSet->sellGroups->each(function (SellGroup $group) use (&$row,$sellSet) {
            $row [] = $sellSet->renderSpanBr($group->forcedSellUnit()->getName() . ' / ' . $group->getCount() . '개', "color: grey");
        });

        $row [] = $sellSet->renderSpanBr($this->getShop()->shop_name);

        return $row;
    }

    function isDelivered()
    {
        return true;
    }

    /**
     * 관리자용 상품등록페이지 지정 (type별로 오버라이딩 할수 있도록 이곳에 구현)
     * @param $vars
     * @return mixed
     */
    public static function getSettingsCreateView($vars) {
        return \XePresenter::make('product.create', $vars);
    }

    /**
     * 관리자용 상품수정페이지 지정 (type별로 오버라이딩 할수 있도록 이곳에 구현)
     * @param $vars
     * @return mixed
     */
    public static function getSettingsEditView($vars) {

        return \XePresenter::make('product.edit', $vars);
    }
}
