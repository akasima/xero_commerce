@section('page_title')
    <h2>상품 수정</h2>
@endsection

<div class="row">
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
                        초기 재고
                        {{ $product->stock }}
                    </div>

                    <div class="form-group">
                        품절 알림 재고
                        {{ $product->alert_stock }}
                    </div>

                    <div class="form-group">
                        <input type="checkbox" checked disabled> 제한없음 <p></p>
                        최소 구매 수량
                        {{ $product->min_buy_count }}

                        최대 구매 수량
                        {{ $product->max_buy_count }}
                    </div>

                    <div class="form-group">
                        설명
                        <textarea readonly>{{ $product->description }}</textarea>
                    </div>
                </div>
            </div>

            <a href="{{ route('xero_commerce::setting.product.edit', ['productId' => $product->id]) }}" class="xe-btn">수정</a>

            <form method="post" action="{{ route('xero_commerce::setting.product.remove', ['productId' => $product->id]) }}">
                {{ csrf_field() }}
                <button type="submit" class="xe-btn xe-btn-danger">삭제</button>
            </form>
        </div>

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    옵션
                </div>

                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>
