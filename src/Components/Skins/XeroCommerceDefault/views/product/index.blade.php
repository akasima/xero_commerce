<div class="row">

    @foreach ($products as $key=>$product)
        @if($key%3 === 0)
            </div>
            <div class="row">
        @endif
        <a href="{{ route('xero_commerce::product.show', ['slug' => $product->getSlug()]) }}">
        <div class="col-lg-4" style="margin-bottom:20px">
            <div>
                <img src="{{$product->getThumbnailSrc()}}" alt="" style="width:100%">
            </div>
            <div>
                @foreach($product->labels as $label)
                    <span class="label label-default">{{$label->name}}</span>
                @endforeach
                <h4>{{$product->name}}</h4>
                <p>{{$product->sub_name}}</p>
                <p style="text-align: center"><span
                        style="text-decoration: line-through">{{ number_format($product->original_price)}}</span> <i
                        class="xi-angle-right"></i> <b>{{number_format($product->sell_price)}}</b></p>
            </div>
        </div>
        </a>
    @endforeach
</div>
<style>
    a {
        color: black;
    }
</style>
