@section('page_title')
    <h2>상품 수정</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}

<div>
    <a href="{{ route('xero_commerce::setting.product.edit', ['productId' => $product->id]) }}" class="xe-btn">수정</a>
    <form method="post" action="{{ route('xero_commerce::setting.product.remove', ['productId' => $product->id]) }}">
        {{ csrf_field() }}
        <button type="submit" class="xe-btn xe-btn-danger">삭제</button>
    </form>
    <a href="{{ route('xero_commerce::setting.product.index') }}" class="xe-btn">목록</a>
</div>

<div id="component-container" class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <th>상품코드</th>
                            <td>{{ $product->product_code }}</td>
                            <th>정상가격</th>
                            <td>{{ number_format($product->original_price ) }}</td>
                            <th>현 재고</th>
                            <td>{{ number_format($product->getStock() ) }}</td>
                        </tr>
                        <tr>
                            <th>상품명</th>
                            <td>{{ $product->name }}</td>
                            <th>판매가격</th>
                            <td>{{ number_format($product->sell_price)  }}</td>
                            <th>품절 알림 재고</th>
                            <td>{{ number_format($product->alert_stock ) }}</td>
                        </tr>
                        <tr>
                            <th>url명</th>
                            <td>{{ $product->getSlug() }}</td>
                            <th>할인율</th>
                            <td>{{$product->discount_percentage}} %</td>
                            <th>최소 구매 수량</th>
                            <td>{{ number_format($product->min_buy_count ) }}</td>
                        </tr>
                        <tr>
                            <th>간략소개</th>
                            <td>{{$product->sub_name}}</td>
                            <th>배송사</th>
                            <td>{{$product->delivery->company->name}}
                                ({{number_format($product->delivery->delivery_fare)}})
                            </td>
                            <th>최대 구매 수량</th>
                            <td>{{ number_format($product->max_buy_count ) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <h3>상품정보</h3>
                </div>

                <div class="panel-body">
                    <table class="table detail_info">
                        <tr>
                            @php
                                $i =0;
                            @endphp
                            @foreach((array)json_decode($product->detail_info) as $key => $val)
                                <th>
                                    {{$key}}
                                </th>
                                <td>
                                    {{$val}}
                                </td>
                                @if($i%2==1)
                        </tr>
                        <tr>
                            @endif
                            @php
                                $i++;
                            @endphp
                            @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <h3>옵션</h3>
                </div>

                <div class="panel-body">
                    <option-table-component :options='{{ json_encode($options) }}'
                                            product-id="{{$product->id}}"
                                            product-price="{{$product->sell_price}}"
                                            save-url="{{route('xero_commerce::setting.product.option.save')}}"
                                            remove-url="{{route('xero_commerce::setting.product.option.remove')}}"
                                            load-url="{{route('xero_commerce::setting.product.option.load',['product'=>$product])}}"></option-table-component>
                </div>
            </div>
        </div>

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <h3>상품설명</h3>
                </div>

                <div class="panel-body">
                    {!! $product->description !!}
                </div>
            </div>
        </div>

        <div class="panel-group">
            @foreach ($product->tags->toArray() as $tag)
                <span class="xe-badge xe-black">#{{ $tag['word'] }}</span>
            @endforeach
        </div>
    </div>
</div>
<style>
    .detail_info th {
        background: #ddd;
        width: 200px
    }
</style>
