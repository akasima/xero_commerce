<?php
    use Xpressengine\Plugins\XeroCommerce\Handlers\ProductHandler;
?>
{{ XeFrontend::css('plugins/xero_commerce/src/Components/Skins/XeroCommerceDefault/assets/css/skin.css')->load() }}
{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/ProductListWidget/Skins/Common/assets/style.css')->load() }}
{{ uio('widgetbox', ['id' => \Xpressengine\Plugins\XeroCommerce\Plugin::XERO_COMMERCE_PREFIX . '-' . $instanceId . '-top', 'link'=>'상단 위젯 편집하기']) }}

<section class="section-basic">
    <div>
        <div class="search-results">
            @if($products->total() == 0)
                <p class="search-results-text">검색된 상품이 존재하지 않습니다.</p>
            @else
                <p class="search-results-text"><span class="search-results-text-num">{{ $products->total() }}</span>개의 상품이
                    검색 되었습니다.</p>
            @endif
        </div>
        <form action="{{url()->current()}}" method="post">
            {{ csrf_field() }}
            <div class="range-box">
                <div class="research-box">
                    <input type="text" name="product_name" class="xe-form-control" placeholder value="{{( request()->product_name)? : ''}}">
                    <button type="submit">
                        <i class="xi-search"></i><span class="xe-sr-only">검색</span>
                    </button>
                </div>

                <div class="xe-dropdown">
                    <button class="xe-btn" type="button" >{{ request()->sort_type? ProductHandler::getSortAble()[request()->sort_type] : '정렬 옵션' }}</button>
                    <ul class="xe-dropdown-menu">
                        <li><a href="#">정렬 옵션</a></li>
                        @foreach (ProductHandler::getSortAble() as $key => $sort)
                            <li><a href="#" value="{{ $key }}">{{ $sort }}</a></li>
                        @endforeach
                    </ul>
                    <input type="hidden" name="sort_type">
                </div>
            </div>
        </form>
        <ul class="list-basic">
            @foreach ($products as $key => $product)
                <li class="item-basic" style="position: relative;">
                    <a href="{{ route('xero_commerce::product.show', ['slug' => $product->getSlug()]) }}" style="overflow: hidden; position:relative;" class="link-basic">
                        @if($badge = $product->badge)
                            <div class="badge" style="background: {{$badge->background_color}};">
                                <span style="color: {{$badge->text_color}};">{{$badge->name}}</span>
                            </div>
                        @endif
                        <span class="thumnail" style="background-image:url('{{$product->getThumbnailSrc()}}')"></span>
                        <div class="box_content">
                            <strong>{{$product->name}}</strong>
                            <p class="price">
                                <span class="xe-sr-only">할인 전</span>
                                <span class="sale">{{ number_format($product->original_price)}}원</span>
                                <span class="xe-sr-only">할인 후</span>
                                <span>{{number_format($product->sell_price)}}원</span>
                            </p>
                            @foreach($product->labels as $label)
                                <span class="xe-shop-tag" @if($label->background_color && $label->text_color)style="background: {{$label->background_color}}; color:{{$label->text_color}}" @endif>{{$label->name}}</span>
                            @endforeach
                        </div>
                    </a>
                    @if($product->state_deal !== \Xpressengine\Plugins\XeroCommerce\Models\Product::DEAL_ON_SALE)
                        <div style="background: rgba(255,255,255,0.5); top:0; position: absolute; width: 100%; height:100%; color:black; font-weight: bold; padding-top:70%; text-align: center">
                            {{$product->getDealStates()[$product->state_deal]}}
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>

    <div class="product_pagination">
        {{ $products->render() }}
    </div>
</section>

<script>
    function toggleHeart(id)
    {
        @if(\Illuminate\Support\Facades\Auth::check())
        $.ajax({
            url: '{{route('xero_commerce::product.wish.toggle',['product'=>''])}}/'+id
        }).done(()=>{
            toggleClass('#heart'+id,'xi-heart-o')
            toggleClass('#heart'+id,'xi-heart')
        })
        @else
        XE.toast('warning','로그인 후 사용할 수 있습니다')
        @endif
    }
    function toggleClass(target, className)
    {
        if ($(target).hasClass(className)){
            $(target).removeClass(className)
        } else {
            $(target).addClass(className)
        }
    }

    $('.xe-dropdown .xe-btn, .xe-dropdown-menu a').on('click', function () {
        toggleClass('.xe-dropdown','open')
    });

    $('.xe-dropdown-menu a').on('click', function (e) {
        e.preventDefault();

        $("input[name=sort_type]").val($(this).attr('value'));

        $("form").submit();
    });
</script>

{{ uio('widgetbox', ['id' => \Xpressengine\Plugins\XeroCommerce\Plugin::XERO_COMMERCE_PREFIX . '-' . $instanceId . '-bottom', 'link'=>'하단 위젯 편집하기']) }}
<style>
    .badge {
        background-color: #444;
        box-shadow: 0 0 3px 2px rgba(0,0,0,0.8);
        height: 100px;
        left: -50px;
        position: absolute;
        top: -50px;
        width: 100px;

        -webkit-transform: rotate(-45deg);
    }

    .badge span {
        color: #f5f5f5;
        font-family: sans-serif;
        font-size: 1.005em;
        left: 12px;
        top: 78px;
        position: absolute;
        width: 80px;
    }
</style>
