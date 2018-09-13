@section('page_title')
    <h2>상품 등록</h2>
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
                        1차 상품 분류
                        {{ $product->first_category_id }}
                    </div>

                    <div class="form-group">
                        2차 상품 분류
                        {{ $product->second_category_id }}
                    </div>

                    <div class="form-group">
                        3차 상품 분류
                        {{ $product->third_category_id }}
                    </div>

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
                        ///////체크박스 이벤트 필요/////////<p></p>
                        <input type="checkbox" checked> 제한없음 <p></p>
                        최소 구매 수량
                        {{ $product->min_buy_count }}

                        최대 구매 수량
                        {{ $product->max_buy_count }}
                    </div>

                    <div class="form-group">
                        설명
                        <textarea name="description" value="{{ $product->description }}"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
