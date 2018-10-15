<?php
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Plugin;
?>

@section('page_title')
    <h2>상품 수정</h2>
@endsection

<form method="post" action="{{ route('xero_commerce::setting.product.update', ['productId' => $product->id]) }}">
    {{ csrf_field() }}
    <button type="submit" class="xe-btn xe-btn-success">등록</button>

    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                            상품 코드 (비워두면 timestamp)
                            <input type="text" name="product_code" value="{{ $product->product_code }}">
                        </div>

                        ///////카테고리 추가 필요/////////

                        <div class="form-group">
                            상품명
                            <input type="text" name="name" value="{{ $product->name }}">

                            <input type="checkbox" name="resetSlug"> slug 변경
                            <input type="text" name="newSlug" value="{{ $product->getSlug() }}">
                        </div>

                        <div class="form-group">
                            간략소개
                            <input type="text" name="sub_name" value="{{ $product->sub_name }}">
                        </div>

                        <div class="form-group">
                            실제 가격
                            <input type="text" name="original_price" value="{{ $product->original_price }}">
                        </div>

                        <div class="form-group">
                            판매 가격
                            <input type="text" name="sell_price" value="{{ $product->sell_price }}">
                        </div>

                        <div class="form-group">
                            할인율 (실제 가격, 판매 가격 변동되면 계산해서 출력하고 출력된 값이나 수정한 값으로 저장)<p></p>
                            <input type="text" name="discount_percentage" value="{{ $product->discount_percentage }}">%
                        </div>

                        <div class="form-group">
                            ///////체크박스 이벤트 필요/////////<p></p>
                            <input type="checkbox" name="buy_count_not_use" checked> 제한없음 <p></p>
                            최소 구매 수량
                            <input type="text" name="min_buy_count" value="{{ $product->min_buy_count }}" disabled="disabled">

                            최대 구매 수량
                            <input type="text" name="max_buy_count" value="{{ $product->max_buy_count }}" disabled="disabled">
                        </div>

                        <div class="form-group">
                            설명
                            {!! editor(Plugin::getId(), [
                              'content' => $product->description,
                              'contentDomName' => 'description',
                            ]) !!}
                        </div>

                        <div class="form-group">
                            과세 유형
                            <select name="tax_type">
                                <option value="">선택</option>
                                @foreach (Product::getTaxTypes() as $value => $taxType)
                                    <option value="{{ $value }}" @if ($value == $product->tax_type) selected @endif>{{ $taxType }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            출력여부
                            <select name="state_display">
                                <option value="">선택</option>
                                @foreach (Product::getDisplayStates() as $value => $displayState)
                                    <option value="{{ $value }}" @if ($value == $product->state_display) selected @endif>{{ $displayState }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            거래여부
                            <select name="state_deal">
                                <option value="">선택</option>
                                @foreach (Product::getDealStates() as $value => $dealState)
                                    <option value="{{ $value }}" @if ($value == $product->state_deal) selected @endif>{{ $dealState }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            라벨
                            @foreach ($labels as $label)
                                <input type="checkbox" name="labels[]" value="{{ $label->id }}" @if (in_array($label->id, $productLabelIds) == true) checked @endif>{{ $label->name }}
                            @endforeach
                        </div>

                        <div class="form-group">
                            뱃지
                            @foreach ($badges as $badge)
                                <input type="radio" name="badge_id" value="{{ $badge->id }}" @if ($product->badge_id == $badge->id) checked @endif> {{ $badge->name }}
                            @endforeach
                        </div>

                        <div class="form-group">
                            {!! uio('uiobject/xero_commerce@tag', [
                                'tags' => $product->tags->toArray()
                            ]) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
