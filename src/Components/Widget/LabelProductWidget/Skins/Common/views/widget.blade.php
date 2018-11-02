{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/LabelProductWidget/Skins/Common/assets/style.css')->load() }}

<div class="xe-shop">
    <h2 class="xe-shop-wiget-title">{{ $widgetConfig['@attributes']['title'] }}</h2>
    <div class="tab-list">
        <ul class="tab-list-title">
            <li class="active"><a href="#">ALL</a></li>
            @foreach ($categories as $category)
                <li><a href="#">{{ xe_trans($category['word']) }}</a></li>
            @endforeach
        </ul>

        <div class="tab-list-container">
            <div class="container">
                <ul class="view1">
                    @foreach ($products as $idx => $product)
                        <li>
                            <a href="{{ route('xero_commerce::product.show', ['slug' => $product->getSlug()]) }}">
                                <div class="tab-list-img">
                                    <div class="tab-list-number">{{ $idx + 1 }}</div>
                                    <img src="{{ $product->getThumbnailSrc() }}" alt="">
                                </div>
                                <div class="tab-list-caption">
                                    <h3 class="default-list-text-title"><span class="xe-shop-tag black">new</span><span class="xe-shop-tag">best</span> {{ $product->name }}</h3>
                                    <p class="default-list-text-price">
                                        <span class="xe-sr-only">할인 전</span>
                                        <span class="through">{{ number_format($product->original_price) }}원</span>
                                        <i class="xi-arrow-right"></i>
                                        <span class="xe-sr-only">할인 후</span>
                                        <span>{{ number_format($product->sell_price) }}원</span>
                                    </p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div><!-- //container  -->
        </div><!-- //tab-list-container  -->
    </div><!-- //tab-list -->
</div><!-- //xe-shop  -->

<script>
    var tab_list_index = 1;
    var tab_list = setInterval(function(){
        if(tab_list_index == 3) {
            tab_list_index = 1;
        } else {
            tab_list_index++;
        }
        $('.tab-list-container ul').removeClass().addClass('view'+tab_list_index);

    }, 3000)
</script>
