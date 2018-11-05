{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/LabelProductWidget/Skins/Common/assets/style.css')->load() }}

<div class="xe-shop">
    <h2 class="xe-shop-wiget-title">{{ $widgetConfig['@attributes']['title'] }}</h2>
    <div class="tab-list">
        <ul class="tab-list-title">
            <li class="active"><a href="#" onclick="return false" id="all">ALL</a></li>
            @foreach ($categories as $category)
                <li><a href="#" onclick="return false" id="{{ $category->id }}">{{ xe_trans($category->word) }}</a></li>
            @endforeach
        </ul>

        <div class="tab-list-container">
            <div class="container">
                <ul id="view_all">
                    @foreach (collect($products)->flatten() as $idx => $product)
                        @if ($idx > 2)
                            @break;
                        @endif

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

                @foreach ($categories as $category)
                    <ul id={{"view_" . $category->id }} style="display:none;">
                        @if (isset($products[$category->id]) == true)
                            @foreach ($products[$category->id] as $idx => $product)
                                @if ($idx > 2)
                                    @break;
                                @endif

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
                        @endif
                    </ul>
                @endforeach
            </div><!-- //container  -->
        </div><!-- //tab-list-container  -->
    </div><!-- //tab-list -->
</div><!-- //xe-shop  -->

<script>


    $('.tab-list-title a').click(function () {
        $('.tab-list-title li').removeClass();
        $(this).parents('li').addClass('active');

        $('.tab-list-container ul').css('display', 'none');
        $('#view_' + $(this).attr('id')).css('display', 'inline-block');
    });
</script>
