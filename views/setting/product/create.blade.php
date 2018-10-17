<?php

use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Plugin;

?>

@section('page_title')
    <h2>상품 등록</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}

<form method="post" action="{{ route('xero_commerce::setting.product.store') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <button type="submit" class="xe-btn xe-btn-success">등록</button>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                            상품 코드 (비워두면 timestamp)
                            <input type="text" name="product_code" value="{{ Request::old('product_code') }}">
                        </div>

                        <div id="component-container" class="form-group">
                            카테고리
                            <category-component :category-items='{{ json_encode($categoryItems) }}'
                                                get-child-url="{{ route('xero_commerce:setting.product.category.getChild') }}"
                                                mode="create">
                            </category-component>
                        </div>

                        <div class="form-group">
                            상품명
                            <input type="text" name="name" value="{{ Request::old('name') }}">
                            <input type="text" name="newSlug" value="{{ Request::old('newSlug') }}" placeholder="slug">
                        </div>

                        <div class="form-group">
                            간략 소개
                            <input type="text" name="sub_name" value="{{ Request::old('sub_name') }}">
                        </div>

                        <div class="form-group">
                            실제 가격
                            <input type="text" name="original_price" value="{{ Request::old('original_price') }}">
                        </div>

                        <div class="form-group">
                            판매 가격
                            <input type="text" name="sell_price" value="{{ Request::old('sell_price') }}">
                        </div>

                        <div class="form-group">
                            할인율 (실제 가격, 판매 가격 변동되면 계산해서 출력하고 출력된 값이나 수정한 값으로 저장)<p></p>
                            <input type="text" name="discount_percentage"
                                   value="{{ Request::old('discount_percentage') }}">%
                        </div>

                        <div class="form-group">
                            초기 재고
                            <input type="text" name="stock" value="{{ Request::old('stock') }}">
                        </div>

                        <div class="form-group">
                            품절 알림 재고
                            <input type="text" name="alert_stock" value="{{ Request::old('alert_stock') }}">
                        </div>

                        <div class="form-group">
                            ///////체크박스 이벤트 필요/////////<p></p>
                            <input type="checkbox" name="buy_count_not_use" checked> 제한없음 <p></p>
                            최소 구매 수량
                            <input type="text" name="min_buy_count" value="{{ Request::old('min_buy_count') }}"
                                   disabled="disabled">

                            최대 구매 수량
                            <input type="text" name="max_buy_count" value="{{ Request::old('max_buy_count') }}"
                                   disabled="disabled">
                        </div>

                        <div class="form-group">
                            과세 유형
                            <select name="tax_type">
                                <option value="">선택</option>
                                @foreach (Product::getTaxTypes() as $value => $taxType)
                                    <option value="{{ $value }}"
                                            @if ($value == Product::TAX_TYPE_TAX) selected @endif>{{ $taxType }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            상품 정보 추가 <i class="xi-plus" style="cursor:pointer" onclick="addProductInfo()"></i>
                            <table class="table" id="productInfoTable">
                                <thead>
                                    <tr>
                                        <th>항목</th>
                                        <th>정보</th>
                                        <th>삭제</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="productInfoTr1">
                                        <td>상품번호</td>
                                        <td>위 상품코드로 자동 기입</td>
                                    </tr>
                                    <tr id="productInfoTr2">
                                        <td><input type='text' name='infoKeys[]' value="제조사"></td>
                                        <td><input type='text' name='infoValues[]'></td>
                                        <td><button onclick="removeProductInfo(2)" class="xe-btn xe-btn-default">삭제</button></td>
                                    </tr>
                                    <tr id="productInfoTr3">
                                        <td><input type='text' name='infoKeys[]' value="상품상태"></td>
                                        <td><input type='text' name='infoValues[]'></td>
                                        <td><button onclick="removeProductInfo(3)" class="xe-btn xe-btn-default">삭제</button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <script>
                                function addProductInfo () {
                                    var id = Number($("#productInfoTable tbody tr").last().attr('id').replace('productInfoTr',''))+1
                                    var tr = "<tr id='productInfoTr"+id+"'>";
                                    tr+="<td><input type='text' name='infoKeys[]'></td>"
                                    tr+="<td><input type='text' name='infoValues[]'></td>"
                                    tr+="<td><button onclick='removeProductInfo("+id+")' class='xe-btn xe-btn-default'>삭제</button></td>"
                                    $("#productInfoTable tbody").append(tr)
                                }
                                function removeProductInfo (id) {
                                    $("#productInfoTable tbody tr#productInfoTr"+id).remove()
                                }
                            </script>
                        </div>

                        <div class="form-group">
                            출력여부
                            <select name="state_display">
                                <option value="">선택</option>
                                @foreach (Product::getDisplayStates() as $value => $displayState)
                                    <option value="{{ $value }}"
                                            @if ($value == Product::DISPLAY_VISIBLE) selected @endif>{{ $displayState }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            거래여부
                            <select name="state_deal">
                                <option value="">선택</option>
                                @foreach (Product::getDealStates() as $value => $dealState)
                                    <option value="{{ $value }}"
                                            @if ($value == Product::DEAL_ON_SALE) selected @endif>{{ $dealState }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            라벨
                            @foreach ($labels as $label)
                                <input type="checkbox" name="labels[]" value="{{ $label->id }}">{{ $label->name }}
                            @endforeach
                        </div>

                        <div class="form-group">
                            뱃지
                            @foreach ($badges as $badge)
                                <input type="radio" name="badge_id" value="{{ $badge->id }}">{{ $badge->name }}
                            @endforeach
                        </div>
                        @for($i=1; $i<=10; $i++)
                            {{ uio('formImage',
                                  ['label'=>'사진업로드 #'.$i,
                                   'name'=>'images[]',
                                   'id'=>'image'.$i,
                                   'description'=> '휴대폰 사용하여 입력하세요'
                            ]) }}
                            @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        설명
        {!! editor(Plugin::getId(), [
          'content' => Request::old('description'),
          'contentDomName' => 'description',
        ]) !!}
    </div>

    <div class="form-group">
        {!! uio('uiobject/xero_commerce@tag', [
            'tags' => []
        ]) !!}
    </div>
</form>
