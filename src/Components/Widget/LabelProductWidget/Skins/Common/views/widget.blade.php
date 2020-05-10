{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/LabelProductWidget/Skins/Common/assets/style.css')->load() }}

<section class="section-label">
    <h2 class="title-label">{{ $widgetConfig['@attributes']['title'] }}</h2>
    <div class="tab-list">
        <ul class="list-tab-rank reset-list">
            <li class="item-tab-rank active"><a href="#"  class="link-tab-rank" onclick="return false" id="all">TOP3</a></li>
            @foreach ($categories as $category)
                <li class="item-tab-rank"><a href="#" class="link-tab-rank" onclick="return false" id="{{ $category->id }}">{{ xe_trans($category->word) }}</a></li>
            @endforeach
        </ul>

        <div>
            <ul class="list-rank reset-list" id="view_all">
                @foreach (collect($products)->flatten() as $idx => $product)
                    @if ($idx > 2)
                        @break;
                    @endif

                    <li class="item-rank">
                        <a href="{{ route('xero_commerce::product.show', ['slug' => $product->slug]) }}" class="link-rank">
                            <div class="thumbnail-rank" style="background-image:url('{{ $product->getThumbnailSrc() }}')">
                                <div class="tab-list-number">{{ $idx + 1 }}</div>
                            </div>
                            <strong class="title-rank"> {{ $product->name }}</strong>
                            <p class="price">
                                <span class="sale">{{ number_format($product->original_price) }}원</span>
                                <span class="">{{ number_format($product->sell_price) }}원</span>
                            </p>
                            @foreach($product->labels as $label)
                                <span class="xe-shop-tag" @if($label->background_color && $label->text_color)style="background: {{$label->background_color}}; color:{{$label->text_color}}" @endif>{{$label->name}}</span>
                            @endforeach
                        </a>
                    </li>
                @endforeach
            </ul>

            @foreach ($categories as $category)
                <ul id="{{"view_" . $category->id }}" class="list-rank reset-list" style="display:none;">
                    @if (isset($products[$category->id]) == true)
                        @foreach ($products[$category->id] as $idx => $product)
                            @if ($idx > 2)
                                @break;
                            @endif

                            <li class="item-rank">
                                <a href="{{ route('xero_commerce::product.show', ['slug' => $product->slug]) }}" class="link-rank">
                                    <div class="thumbnail-rank" style="background-image:url('{{ $product->getThumbnailSrc() }}')">
                                        <div class="tab-list-number">{{ $idx + 1 }}</div>
                                    </div>
                                    <strong class="title-rank"> {{ $product->name }}</strong>
                                    <p class="price">
                                        <span class="sale">{{ number_format($product->original_price) }}원</span>
                                        <span class="">{{ number_format($product->sell_price) }}원</span>
                                    </p>
                                    @foreach($product->labels as $label)
                                        <span class="xe-shop-tag" @if($label->background_color && $label->text_color)style="background: {{$label->background_color}}; color:{{$label->text_color}}" @endif>{{$label->name}}</span>
                                    @endforeach
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            @endforeach
        </div><!-- //container  -->
    </div><!-- //tab-list -->
</section>

<script>


    $('.item-tab-rank a').click(function () {
        $('li.item-tab-rank').removeClass("active");
        $(this).parents('li').addClass('active');

        $('ul.list-rank').css('display', 'none');
        $('#view_' + $(this).attr('id')).css('display', 'flex');
    });
</script>
