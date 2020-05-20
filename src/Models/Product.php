<?php

namespace Xpressengine\Plugins\XeroCommerce\Models;

use App\Facades\XeMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Xpressengine\Category\Models\CategoryItem;
use Xpressengine\Plugins\XeroCommerce\Services\ProductCategoryService;
use Xpressengine\Plugins\XeroCommerce\Traits\CustomTableInheritanceTrait;
use Xpressengine\Tag\Tag;
use Xpressengine\Plugins\XeroCommerce\Models\Products\BasicProduct;
use Xpressengine\Plugins\XeroCommerce\Models\Products\DigitalProduct;

class Product extends Model
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

    const SHIPPING_FEE_TYPE = [
        '선불' => 1,
        '착불' => 2
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    protected $table = 'xero_commerce__products';

    protected $fillable = [
        'shop_id',
        'type',
        'product_code',
        'name',
        'original_price',
        'sell_price',
        'discount_percentage',
        'min_buy_count',
        'max_buy_count',
        'description',
        'badge_id',
        'tax_type',
        'option_type',
        'state_display',
        'state_deal',
        'sub_name',
        'shop_carrier_id',
        'detail_info'
    ];

    protected static $singleTableTypeField = 'type';

    protected static $singleTableSubclasses = [BasicProduct::class, DigitalProduct::class];

    /**
     * Relationships
     * 모델관계
     */

    /**
     * 관계 : 품목 (기본 판매 단위)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'id');
    }

    /**
     * 관계+조건 : 전시중인 품목
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function visibleVariants()
    {
        return $this->variants()->where('state_display', ProductVariant::DISPLAY_VISIBLE);
    }

    /**
     * 관계 : 상점
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * 관계 : 상품이미지
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function images()
    {
        return $this->morphToMany(\Xpressengine\Media\Models\Image::class, 'imagable', (new Image)->getTable());
    }

    /**
     * 관계 : 상점에 등록된 배송업체
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shopCarrier()
    {
        return $this->belongsTo(ShopCarrier::class, 'shop_carrier_id');
    }

    /**
     * 관계 : Variant생성을 위한 조합용 옵션
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(ProductOption::class, 'product_id', 'id');
    }

    /**
     * 관계 : 커스텀 옵션 (사용자가 직접 입력)
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customOptions()
    {
        return $this->hasMany(ProductCustomOption::class, 'product_id', 'id');
    }

    /**
     * 관계 : 주문내역
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    /**
     * 관계 : 위시리스트
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wishes()
    {
        return $this->hasMany(Wish::class, 'product_id');
    }

    /**
     * 관계 : 태그
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'taggables', 'taggable_id', 'tag_id');
    }

    /**
     * Utility functions
     * 유틸 함수들
     */

    /**
     * 전시중인 품목을 Array형식(Json출력용)으로 가져오는 함수
     * @return mixed
     */
    public function getVisibleVariantsArray()
    {
        return $this->visibleVariants->map(function (ProductVariant $variant) {
            return $variant->getJsonFormat();
        });
    }

    /**
     * 뷰에 넘길때 사용할 JSON형식
     * @return array
     */
    public function getJsonFormat()
    {
        return [
            'id' => $this->id,
            'mainImage' => $this->getThumbnailSrc(),
            'images' => $this->getImages(),
            'contents' => $this->getContents(),
            'data' => $this,
            'shop' => $this->shop,
            'variants' => $this->getVisibleVariantsArray(),
            'shopCarrier' => $this->getShopCarrier(),
            'url' => $this->slugUrl(),
            'labels' => $this->labels,
            'badge' => $this->badges,
            'categories' => $this->getCategories(),
        ];
    }

    /**
     * 상점에 등록된 배송회사를 회사정보와 함께 가져오는 함수
     * @return mixed
     */
    public function getShopCarrier()
    {
        return $this->shopCarrier->load('carrier');
    }

    /**
     * 이미지 URL
     * @return \Illuminate\Support\Collection
     */
    function getImages()
    {
        if ($this->images->count() === 0) {
            return collect([asset('/assets/core/common/img/default_image_1200x800.jpg')]);
        }

        return $this->images->map(function ($item) {
            return XeMedia::images()->getThumbnail($item, 'widen', 'B')->url();
        });
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
        // TODO : variants에 있는 옵션들만 출력되도록 구현필요
        return $this->options;
    }

    public function getAvailableCustomOptions()
    {
        return $this->customOptions;
    }

    function getCategories()
    {
        $productCategoryService = new ProductCategoryService();
        return $productCategoryService->getProductCategoryTree($this->id);
    }

    public function labels()
    {
        return $this->hasManyThrough(Label::class, ProductLabel::class, 'product_id', 'id', 'id', 'label_id');
    }

    public function badge()
    {
        return $this->hasOne(Badge::class, 'id', 'badge_id');
    }

    public function getStock()
    {
        return $this->variants()->sum('stock');
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
        return route('xero_commerce::product.show', ['strSlug' => $this->slug]);
    }

    function renderForOrderableItem(OrderableItem $orderableItem)
    {
        $row = [];
        $row [] = '<a target="_blank' . now()->toTimeString() . '" href="' . route('xero_commerce::product.show', ['strSlug' => $this->slug]) . '">' . $orderableItem->renderSpanBr($this->getName()) . '</a>';
        $row [] = $orderableItem->renderSpanBr($this->getInfo());

        $row [] = $orderableItem->renderSpanBr($orderableItem->productWithTrashed->name . ' / ' . $group->count . '개', "color: grey");

        $row [] = $orderableItem->renderSpanBr($this->shop->shop_name);

        return $row;
    }

    function isShipped()
    {
        return true;
    }

    public function getFare()
    {
        return $this->shopCarrier->fare;
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
