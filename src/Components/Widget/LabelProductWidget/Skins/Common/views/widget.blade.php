{{ XeFrontend::css('plugins/xero_commerce/src/Components/Widget/LabelProductWidget/Skins/Common/assets/style.css')->load() }}
<div class="label_widget">
    <div class="title">
        <span>{{ $widgetConfig['@attributes']['title'] }}</span>
    </div>

    <div>
        <a href="#">ALL</a>
        @foreach ($categories as $category)
            <a href="#">{{ xe_trans($category['word']) }}</a>
        @endforeach
    </div>

    <div>
        @foreach ($products as $product)
            <div>
                <a href="{{ route('xero_commerce::product.show', ['slug' => $product->getSlug()]) }}">{{ $product->name }}</a>
            </div>
        @endforeach
    </div>
</div>
