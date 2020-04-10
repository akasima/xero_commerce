<?php
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Plugin;
?>

@section('page_title')
    <h2>상품 등록</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
{{ XeFrontend::js('https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js')->appendTo('body')->load() }}

<form id="save" method="post" action="{{ route('xero_commerce::setting.product.store') }}" enctype="multipart/form-data"
      data-rule="product" data-rule-alert-type="toast">
    {{ csrf_field() }}
    <input type="hidden" name="type" value="{{$type}}" />

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
    <div class="xe-row">
        <div class="xe-col-sm-12">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#상품정보Section" aria-expanded="false">
                        <a data-toggle="collapse" data-target="#상품정보Section" class="btn-link panel-toggle" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">상품정보</h3>
                    </div>
                    <div id="상품정보Section" class="panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group __xe-input-group">
                            <label for="pwd" class ="control-label col-sm-3">상품명</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="name" data-valid-name="상품명" value="{{ Request::old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">간략 소개</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="sub_name" data-valid-name="간략 소개" value="{{ Request::old('sub_name') }}">
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-3">카테고리</label>
                            <div class="col-sm-4">
                                <category-component :category-items='{{ json_encode($categoryItems) }}'
                                                    get-child-url="{{ route('xero_commerce:setting.product.category.getChild') }}"
                                                    name="newCategory"
                                                    mode="create">
                                </category-component>
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-3">라벨</label>
                            <div class="col-sm-8">
                                @foreach ($labels as $label)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="labels[]" data-valid-name="라벨" value="{{ $label->id }}" @if (in_array($label->id, Request::old('labels', [])) == true) checked @endif>{{ $label->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-3">배지</label>
                            <div class="col-sm-8">
                                <label class="radio-inline">
                                    <input type="radio" name="badge_id" value="" checked>사용 안함
                                </label>
                                @foreach ($badges as $badge)
                                    <label class="radio-inline">
                                        <input type="radio" name="badge_id" data-valid-name="배지" value="{{ $badge->id }}" @if (Request::old('badge_id') == $badge->id) checked @endif>{{ $badge->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">상품 코드</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="product_code" data-valid-name="상품 코드" value="{{ Request::old('product_code') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#가격정보Section" href="#collapseTwo">
                        <a data-toggle="collapse" data-target="#가격정보Section" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">가격정보</h3>
                    </div>
                    <div id="가격정보Section" class="panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group">
                            <label class ="control-label col-sm-3">정상 가격</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control must-price" name="original_price" data-valid-name="정상 가격" value="{{ Request::old('original_price') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">판매 가격</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control must-price" name="sell_price" data-valid-name="판매 가격" value="{{ Request::old('sell_price') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">할인율</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="discount_percentage" data-valid-name="할인율" value="{{ Request::old('discount_percentage') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#배송정보Section" aria-expanded="false">
                        <a data-toggle="collapse" data-target="#배송정보Section" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">배송정보</h3>
                    </div>
                    <div id="배송정보Section" class="panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group">
                            <label class ="control-label col-sm-3">배송사</label>
                            <div class="col-sm-8">
                                <select name="shop_delivery_id" class="form-control" data-valid-name="배송사">
                                    @php
                                        //TODO 입점몰이 여러개일 때 처리
                                        $deliverys = $shops[0]->deliveryCompanys;
                                    @endphp
                                    <option value="">선택</option>
                                    @foreach($deliverys as $delivery)
                                        @if(Request::old('shop_delivery_id'))
                                        <option value="{{$delivery->pivot->id}}" @if (Request::old('shop_delivery_id') == $delivery->pivot->id) selected @endif>{{$delivery->name}}({{number_format($delivery->pivot->delivery_fare)}})</option>
                                        @else
                                        <option value="{{$delivery->pivot->id}}" @if (array_first($deliverys)->id == $delivery->id) selected @endif>{{$delivery->name}}({{number_format($delivery->pivot->delivery_fare)}})</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#재고정보Section" aria-expanded="false">
                        <a data-toggle="collapse" data-target="#재고정보Section" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">재고정보</h3>
                    </div>
                    <div id="재고정보Section" class="panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group">
                            <label class ="control-label col-sm-3">초기 재고</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="stock" data-valid-name="초기 재고" value="{{ Request::old('stock') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">품절 알림 재고</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="alert_stock" data-valid-name="품절 알림 재고" value="{{ Request::old('alert_stock') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">최소 구매 수량</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="min_buy_count" data-valid-name="최소 구매 수량" value="{{ Request::old('min_buy_count') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">최대 구매 수량</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="max_buy_count" data-valid-name="최대 구매 수량" value="{{ Request::old('max_buy_count') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">과세 유형</label>
                            <div class="col-sm-8">
                                @foreach (Product::getTaxTypes() as $key => $type)
                                    <label class="radio-inline">
                                        <input type="radio" name="tax_type" data-valid-name="과세 유형" value="{{ $key }}"
                                            @if (Request::old('tax_type') == $key) checked @endif>{{ $type }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#옵션Section" aria-expanded="false">
                        <a data-toggle="collapse" data-target="#옵션Section" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">옵션</h3>
                    </div>

                    <div id="옵션Section" class="panel-body panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group component-container">
                            <div class="col-sm-12">
                                <option-table-component :options="{{ json_encode(Request::old('options',[])) }}"
                                                        :option-items="{{ json_encode(Request::old('option_items',[])) }}"
                                                        product-option-type="{{Request::old('option_type')}}"></option-table-component>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#추가옵션Section" aria-expanded="false">
                        <a data-toggle="collapse" data-target="#추가옵션Section" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">추가옵션</h3>
                    </div>

                    <div id="추가옵션Section" class="panel-body panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group component-container">
                            <div class="col-sm-12">
                                <custom-option-table-component :custom-options="{{ json_encode(Request::old('custom_options',[])) }}"
                                                        :types="{{ json_encode($customOptionTypes) }}"></custom-option-table-component>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#추가정보Section" aria-expanded="false">
                        <a data-toggle="collapse" data-target="#추가정보Section" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">추가정보</h3>
                    </div>
                    <div id="추가정보Section" class="panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group">
                            <label class ="control-label col-sm-3">상품 정보 추가</label>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <button type="button" class="btn btn-sm btn-primary" onclick="addProductInfo()"><i class="xi-plus"></i> 항목 추가</button>
                                </div>
                                <div class="table-scrollable">
                                    <table class="table table-striped" id="productInfoTable">
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
                                            <td></td>
                                        </tr>
                                        <tr id="productInfoTr2">
                                            <td><input type='text' class="form-control" name='infoKeys[]' value="제조사"></td>
                                            <td><input type='text' class="form-control" name='infoValues[]'></td>
                                            <td>
                                                <button onclick="removeProductInfo(2)" class="btn btn-default btn-sm">삭제</button>
                                            </td>
                                        </tr>
                                        <tr id="productInfoTr3">
                                            <td><input type='text' class="form-control" name='infoKeys[]' value="상품상태"></td>
                                            <td><input type='text' class="form-control" name='infoValues[]'></td>
                                            <td>
                                                <button onclick="removeProductInfo(3)" class="btn btn-default btn-sm">삭제</button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3"> 사진업로드 </label>
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary" onclick="event.preventDefault();addImage()"><i class="xi-plus"></i>이미지 추가</button>
                                </div>
                                @for($i=1; $i<=10; $i++)
                                    <div class="row">
                                        <div class="col-lg-12 form-image @if($i==1) open @endif" id="form-image-{{$i}}">
                                            {{ uio('formImage',
                                                ['label'=>($i===1) ? '메인이미지' :'추가이미지 #'.($i-1),
                                                'name'=>'images[]',
                                                'id'=>'image'.$i,
                                                'maxSize'=>\Xpressengine\Plugins\XeroCommerce\Models\Product::IMG_MAXSIZE
                                            ]) }}
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">상품 소개</label>
                            <div class="col-sm-8">
                                {!! editor(Plugin::getId(), [
                                   'content' => Request::old('description'),
                                   'contentDomName' => 'description',
                                 ]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">태그등록 </label>
                            <div class="col-sm-8">
                                {!! uio('uiobject/xero_commerce@tag', [
                                    'tags' => []
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="xero-settings-control-float">

            <button style="float:left" type="button" class="btn btn-success toggle-required">필수값 노출/비노출</button>
            <label>출력 여부</label>
            <select name="state_display" data-valid-name="출력 여부">
                @foreach (Product::getDisplayStates() as $key => $type)
                    <option value="{{ $key }}" @if (Request::old('state_display') == $key) selected @endif>{{ $type }}</option>
                @endforeach
            </select>
            <label>거래 여부</label>
            <select name="state_deal" data-valid-name="거래 여부">
                @foreach (Product::getDealStates() as $key => $type)
                    <option value="{{ $key }}" @if (Request::old('state_deal') == $key) selected @endif>{{ $type }}</option>
                @endforeach
            </select>
        <button type="submit" class="btn btn-primary btn-lg">저장</button>
    </div>

</form>
<form id="temp" method="post" action="{{ route('xero_commerce::setting.product.temp') }}" enctype="multipart/form-data"
      style="display:none">

</form>

<script>
    $(function () {
        $('textarea[name=description]').attr('data-valid-name', '상품 소개');
    })
</script>
<script>
    function addProductInfo() {
        var id = Number($("#productInfoTable tbody tr").last().attr('id').replace('productInfoTr', '')) + 1
        var tr = "<tr id='productInfoTr" + id + "'>";
        tr += "<td><input type='text' class='form-control' name='infoKeys[]'></td>"
        tr += "<td><input type='text' class='form-control' name='infoValues[]'></td>"
        tr += "<td><button onclick='removeProductInfo(" + id + ")' class='btn btn-default btn-sm'>삭제</button></td>"
        $("#productInfoTable tbody").append(tr)
    }

    function removeProductInfo(id) {
        $("#productInfoTable tbody tr#productInfoTr" + id).remove()
    }
    function tempSubmit()
    {
        $("form#temp").html($("form#save").html());
        $("form#temp").submit();
    }

    function addImage(event)
    {
        if($(".form-image").not(".open").length === 0) {
            alert('메인 페이지를 포함 최대 10개까지 업로드할 수 있습니다.')
            return
        }
        $(".form-image").not(".open").first().find("input[type=file]").prop("disabled",false)
        $(".form-image").not(".open").first().addClass("open")

    }

    function removeImage(targetId)
    {
        $("#form-image-"+targetId).find("input[type=file]").prop("disabled",true)
        $("#form-image-"+targetId).removeClass("open")
    }
</script>

<script>
    $(function () {
        $("[name=shop_id]").change(function () {
            $("[name=shop_delivery_id]").val("");
            $("[name=shop_delivery_id] option").not(':selected').remove();
            $.ajax({
                url: '{{route('xero_commerce::setting.config.shop.delivery',['shop'=>''])}}/' + $("[name=shop_id]").val(),
            }).done(function (res) {
                $.each(res, function(k,v) {
                    $("[name=shop_delivery_id]").append('<option value="' + v.pivot.id + '">' + v.name + '(' + Number(v.pivot.delivery_fare).toLocaleString() + ')</option>');
                })
            }).fail(function (res)  {

            })
        });
        $('.must-price').mask('000,000,000,000,000,000', {reverse: true});
        $('.toggle-required').click(function(e){
            e.stopPropagation();
            e.preventDefault();
            $('label.control-label').not('.xe-form__label--requried').parent().not('.xero-settings-control-float').toggle();
        }).trigger('click');
    })
</script>
<style>
    .col-lg-4:nth-child(3n+1) {
    }
    .control-label{
        text-align: right;
    }
    .panel-collapse{
        padding:10px;
    }
    .form-image{
        display: none;
    }

    .form-image.open{
        display: block;
    }
    .actually-not-required:after {
        content: none;
    }
</style>
