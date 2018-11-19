<?php
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Plugin;
?>

@section('page_title')
    <h2>상품 등록</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}

<form method="post" action="{{ route('xero_commerce::setting.product.store') }}" enctype="multipart/form-data"
      data-rule="product" data-rule-alert-type="toast">
    {{ csrf_field() }}
    <button type="submit" class="xe-btn xe-btn-success">등록</button>
    <div class="form-group">
        @if(count($shops)>1)
            <label>입점몰</label>
            <select name="shop_id" class="form-control">
                @foreach($shops as $shop)
                    <option value="{{$shop->id}}">{{$shop->shop_name}}</option>
                @endforeach
            </select>
        @else
            <h4>{{$shops[0]->shop_name}}</h4>
            <input type="hidden" name="shop_id" value="{{$shops[0]->id}}">
        @endif
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-body">

                    {{uio('formText', [
                    'label'=>'상품 코드',
                    'name'=>'product_code',
                    'description'=>'비워두면 자동 기입됩니다',
                    'value'=>Request::old('product_code')
                    ])}}

                    <div id="component-container" class="form-group">
                        <label>카테고리</label>
                        <category-component :category-items='{{ json_encode($categoryItems) }}'
                                            get-child-url="{{ route('xero_commerce:setting.product.category.getChild') }}"
                                            mode="create">
                        </category-component>
                    </div>

                    {{uio('formText', [
                    'label'=>'상품명',
                    'name'=>'name',
                    'description'=>'상품명입니다.',
                    'data-valid-name' => '상품명',
                    'value' => Request::old('name')
                    ])}}

                    {{uio('formText', [
                    'label'=>'url명',
                    'name'=>'newSlug',
                    'description'=>'상품 링크 Url로 등록되는 이름입니다.(비워두면 자동으로 설정 됩니다.)',
                    'value' => Request::old('newSlug')
                    ])}}

                    {{uio('formText', [
                    'label'=>'간략 소개',
                    'name'=>'sub_name',
                    'data-valid-name' => '간략 소개',
                    'description'=>'상품명 아래에 위치하는 소개말입니다.',
                    'value' => Request::old('sub_name')
                    ])}}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-body">
                    {{uio('formText', [
                    'label'=>'정상 가격',
                    'name'=>'original_price',
                    'description'=>'상품의 정상가격으로 표기됩니다.',
                    'data-valid-name' => '정상 가격',
                    'value' => Request::old('original_price')
                    ])}}

                    {{uio('formText', [
                    'label'=>'판매 가격',
                    'name'=>'sell_price',
                    'description'=>'실제로 판매하는 가격입니다.',
                    'data-valid-name' => '판매 가격',
                    'value'=>Request::old('sell_price')
                    ])}}

                    {{uio('formText', [
                    'label'=>'할인율',
                    'name'=>'discount_percentage',
                    'description'=>'실제 가격, 판매 가격할인율과 근사하게 표기하고싶은 숫자를 직접 적습니다. 비워두면 실제 계산값을 소숫점 두자리까지 저장합니다.',
                    'value'=>Request::old('discount_percentage')
                    ])}}

                    <div class="form-group">
                        <label>배송사</label>
                        <select name="shop_delivery_id" class="form-control" data-valid-name="배송사">
                            @php
                                //TODO 입점몰이 여러개일 때 처리
                                $deliverys = $shops[0]->deliveryCompanys;
                            @endphp
                            <option value="">선택</option>
                            @foreach($deliverys as $delivery)
                                <option value="{{$delivery->pivot->id}}" @if (Request::old('shop_delivery_id') == $delivery->pivot->id) selected @endif>{{$delivery->name}}({{number_format($delivery->pivot->delivery_fare)}})</option>
                            @endforeach
                        </select>
                    </div>
                    <script>
                        $(function () {
                            $("[name=shop_id]").change(function () {
                                $("[name=shop_delivery_id]").val("");
                                $("[name=shop_delivery_id] option").not(':selected').remove();
                                $.ajax({
                                    url: '{{route('xero_commerce::setting.config.shop.delivery',['shop'=>''])}}/' + $("[name=shop_id]").val(),
                                }).done(res => {
                                    $.each(res, (k, v) => {
                                        $("[name=shop_delivery_id]").append('<option value="' + v.pivot.id + '">' + v.name + '(' + Number(v.pivot.delivery_fare).toLocaleString() + ')</option>');
                                    })
                                }).fail(res => {

                                })
                            })
                        })
                    </script>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-body">
                    {{uio('formText', [
                    'label'=>'초기 재고',
                    'name'=>'stock',
                    'description'=>'현재 재고량을 표기합니다.',
                    'data-valid-name' => '초기 재고',
                    'value'=>Request::old('stock')
                    ])}}
                    {{uio('formText', [
                    'label'=>'품절 알림 재고',
                    'name'=>'alert_stock',
                    'description'=>'이 이하로 떨어지면 품절알림을 합니다.',
                    'value'=>Request::old('alert_stock')
                    ])}}
                    <input type="checkbox" name="buy_count_not_use" checked> 제한없음 <p></p>
                    {{uio('formText', [
                    'label'=>'최소 구매 수량',
                    'name'=>'min_buy_count',
                    'description'=>'최소로 구매해야하는 수량입니다.',
                    'value'=>Request::old('min_buy_count')
                    ])}}
                    {{uio('formText', [
                    'label'=>'최대 구매 수량',
                    'name'=>'max_buy_count',
                    'description'=>'최대로 구매할 수 있는 수량입니다.',
                    'value'=>Request::old('max_buy_count')
                    ])}}
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel">
                @php
                    function formatArray ($array){
                        return collect($array)->map(function($item,$key){
                                return [
                                'text'=>$item,
                                'value'=>$key];
                                });
                    }
                @endphp
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <label>과세 유형</label>
                            <select class="form-control" name="tax_type">
                                @foreach (Product::getTaxTypes() as $key => $type)
                                    <option value="{{ $key }}" @if (Request::old('tax_type') == $key) selected @endif>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4">
                            <label>출력 여부</label>
                            <select class="form-control" name="state_display">
                                @foreach (Product::getDisplayStates() as $key => $type)
                                    <option value="{{ $key }}" @if (Request::old('state_display') == $key) selected @endif>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4">
                            <label>거래 여부</label>
                            <select class="form-control" name="state_deal">
                                @foreach (Product::getDealStates() as $key => $type)
                                    <option value="{{ $key }}" @if (Request::old('state_deal') == $key) selected @endif>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>라벨</label>
                                @foreach ($labels as $label)
                                    <input type="checkbox" name="labels[]" value="{{ $label->id }}" @if (in_array($label->id, Request::old('labels', [])) == true) checked @endif>{{ $label->name }}
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label>뱃지</label>
                            <input type="radio" name="badge_id" value="" checked>사용 안함
                            @foreach ($badges as $badge)
                                <input type="radio" name="badge_id" value="{{ $badge->id }}" @if (Request::old('badge_id') == $badge->id) checked @endif>{{ $badge->name }}
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label>상품 정보 추가 <i class="xi-plus" style="cursor:pointer"
                                           onclick="addProductInfo()"></i></label>
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
                                <td>
                                    <button onclick="removeProductInfo(2)" class="xe-btn xe-btn-default">삭제</button>
                                </td>
                            </tr>
                            <tr id="productInfoTr3">
                                <td><input type='text' name='infoKeys[]' value="상품상태"></td>
                                <td><input type='text' name='infoValues[]'></td>
                                <td>
                                    <button onclick="removeProductInfo(3)" class="xe-btn xe-btn-default">삭제</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <script>
                            function addProductInfo() {
                                var id = Number($("#productInfoTable tbody tr").last().attr('id').replace('productInfoTr', '')) + 1
                                var tr = "<tr id='productInfoTr" + id + "'>";
                                tr += "<td><input type='text' name='infoKeys[]'></td>"
                                tr += "<td><input type='text' name='infoValues[]'></td>"
                                tr += "<td><button onclick='removeProductInfo(" + id + ")' class='xe-btn xe-btn-default'>삭제</button></td>"
                                $("#productInfoTable tbody").append(tr)
                            }

                            function removeProductInfo(id) {
                                $("#productInfoTable tbody tr#productInfoTr" + id).remove()
                            }
                        </script>
                    </div>
                    <div class="row">
                        @for($i=1; $i<=10; $i++)
                            <div class="col-lg-4">
                                {{ uio('formImage',
                                      ['label'=>'사진업로드 #'.$i,
                                       'name'=>'images[]',
                                       'id'=>'image'.$i,
                                       'maxSize'=>50000000
                                ]) }}
                            </div>
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
    <button type="submit" class="xe-btn xe-btn-success">등록</button>
</form>

<script>
    $(function () {
        $('textarea[name=description]').attr('data-valid-name', '상품 소개');
    })
</script>
