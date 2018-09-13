<?php
use Xpressengine\Plugins\XeroStore\Models\Product;
?>

@section('page_title')
    <h2>상품 등록</h2>
@endsection

<form method="post" action="{{ route('xero_store::setting.product.store') }}">
    {{ csrf_field() }}
    <button type="submit" class="xe-btn xe-btn-success">등록</button>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                            상품 코드
                            <input type="text" name="product_code" value="{{ Request::old('product_id') }}">
                        </div>

                        ///////분류 카테고리로 변환 필요/////////
                        <div class="form-group">
                            1차 상품 분류
                            <input type="text" name="first_category_id" value="{{ Request::old('first_category_id') }}">
                        </div>

                        <div class="form-group">
                            2차 상품 분류
                            <input type="text" name="second_category_id" value="{{ Request::old('second_category_id') }}">
                        </div>

                        <div class="form-group">
                            3차 상품 분류
                            <input type="text" name="third_category_id" value="{{ Request::old('third_category_id') }}">
                        </div>

                        <div class="form-group">
                            상품명
                            <input type="text" name="name" value="{{ Request::old('name') }}">
                        </div>

                        <div class="form-group">
                            가격
                            <input type="text" name="price" value="{{ Request::old('price') }}">
                        </div>

                        <div class="form-group">
                            초기 재고
                            <input type="text" name="stock" value="{{ Request::old('stock') }}">
                        </div>

                        <div class="form-group">
                            품절 알림 재고
                            <input type="text" name="alert_stock" value="{{ Request::old('alsert_stock') }}">
                        </div>

                        <div class="form-group">
                            ///////체크박스 이벤트 필요/////////<p></p>
                            <input type="checkbox" name="buy_count_not_use" checked> 제한없음 <p></p>
                            최소 구매 수량
                            <input type="text" name="min_buy_count" value="{{ Request::old('min_buy_count') }}" disabled="disabled">

                            최대 구매 수량
                            <input type="text" name="max_buy_count" value="{{ Request::old('max_buy_count') }}" disabled="disabled">
                        </div>

                        <div class="form-group">
                            설명
                            <textarea name="description" value="{{ Request::old('description') }}"></textarea>
                        </div>

                        <div class="form-group">
                            출력여부
                            <select name="state_display">
                                <option value="">선택</option>
                                @foreach ($displayStates as $value => $displayState)
                                    <option value="{{ $value }}" @if ($value == Product::DISPLAY_VISIBLE) selected @endif>{{ $displayState }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            거래여부
                            <select name="state_deal">
                                <option value="">선택</option>
                                @foreach ($dealStates as $value => $dealState)
                                    <option value="{{ $value }}" @if ($value == Product::DEAL_ON_SALE) selected @endif>{{ $dealState }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
