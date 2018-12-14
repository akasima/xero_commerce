<h3 class="xe-sr-only">상품 상세 정보</h3>
<div class="detail-information-table">
    <div class="detail-information-row">
        @php
            $i=0;
        @endphp
        @foreach(json_decode($product->detail_info) as $key=>$value)
            <div class="detail-information-cell th">{{$key}}</div>
            <div class="detail-information-cell">{{$value}}</div>
            @if($i%2===1)
    </div>
    <div class="detail-information-row">
        @endif
        @php
            $i++
        @endphp
        @endforeach
    </div>
</div>
<div class="detail-information-view">
    {!! $product->description !!}
</div>
