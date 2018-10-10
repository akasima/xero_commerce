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
                    <div class="form-group">
                        상품 코드
                        {{ $product->product_code }}
                    </div>

                    ///////분류 카테고리로 변환 필요/////////

                    <div class="form-group">
                        상품명
                        {{ $product->name }}
                    </div>

                    <div class="form-group">
                        가격
                        {{ $product->price }}
                    </div>

                    <div class="form-group">
                        <input type="checkbox" checked disabled> 제한없음 <p></p>
                        최소 구매 수량
                        {{ $product->min_buy_count }}

                        최대 구매 수량
                        {{ $product->max_buy_count }}
                    </div>

                    <div class="form-group">
                        과세 유형
                        {{ $product->getTaxTypeName() }}
                    </div>

                    <div class="form-group">
                        설명
                        {!! $product->description !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    옵션
                </div>

                <div class="panel-body">
                    <option-table-component :options='{{ json_encode($options) }}'></option-table-component>
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
