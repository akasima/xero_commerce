<?php

use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Plugin;

?>

@section('page_title')
    <h2>상품 수정</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->before('plugins/board/assets/js/BoardTags.js')->appendTo('body')->load() }}
{{ XeFrontend::js('https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js')->appendTo('body')->load() }}

<form id="save" method="post" action="{{ route('xero_commerce::setting.product.update', ['productId' => $product->id]) }}"
      enctype="multipart/form-data"
      data-rule="product" data-rule-alert-type="toast">
    {{ csrf_field() }}
    <div class="xe-row">
        <div class="xe-col-sm-12">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#상품정보Section" aria-expanded="false">
                        <a data-toggle="collapse" data-target="#상품정보Section" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">상품정보</h3>
                    </div>
                    <div id="상품정보Section" class="panel-body panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group">
                            <label for="pwd" class ="control-label col-sm-2">상품명</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" data-valid-name="상품명" value="{{ $product->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">간략 소개</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="sub_name" data-valid-name="간략 소개" value="{{ $product->sub_name }}">
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-2">카테고리</label>
                            <div class="col-sm-4">
                                <category-component :category-items='{{ json_encode($categoryItems) }}'
                                                    get-child-url="{{ route('xero_commerce:setting.product.category.getChild') }}"
                                                    mode="create"
                                                    name="newCategory"
                                                    :selected="{{ json_encode($productCategorys)}}">
                                </category-component>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">라벨</label>
                            <div class="col-sm-9">
                                @foreach ($labels as $label)
                                    <label class="checkbox-inline">
                                        <input type="checkbox" name="labels[]" value="{{ $label->id }}"
                                           @if (in_array($label->id, $productLabelIds) == true) checked @endif>{{ $label->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">배지</label>
                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" name="badge_id" value="" @if ($product->badge_id == '') checked @endif>사용
                                </label>
                                안함
                                @foreach ($badges as $badge)
                                    <label class="radio-inline">
                                        <input type="radio" name="badge_id" value="{{ $badge->id }}"
                                           @if ($product->badge_id == $badge->id) checked @endif>{{ $badge->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">상품 코드</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="product_code" data-valid-name="상품 코드" value="{{ $product->product_code }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#가격정보Section" aria-expanded="false">
                        <a data-toggle="collapse" data-target="#가격정보Section" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">가격정보</h3>
                    </div>
                    <div id="가격정보Section" class="panel-body panel-collapse collapse in" role="tabpanel" aria-expanded="false">

                        <div class="form-group">
                            <label class ="control-label col-sm-2">정상 가격</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control must-price" name="original_price" data-valid-name="정상 가격" value="{{ $product->original_price }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">판매 가격</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control must-price" name="sell_price" data-valid-name="판매 가격" value="{{ $product->sell_price }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">할인율</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="discount_percentage" data-valid-name="할인율" value="{{ $product->discount_percentage }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading" data-toggle="collapse" data-target="#배송정보Section" aria-expanded="false">
                        <a data-toggle="collapse" data-target="#배송정보Section" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        <h3 class="panel-title">배송정보</h3>
                    </div>
                    <div id="배송정보Section" class="panel-body panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group">
                            <label class ="control-label col-sm-2">배송사</label>
                            <div class="col-sm-9">
                                <select name="shop_delivery_id" class="form-control" data-valid-name="배송사">
                                    @php
                                        $deliverys = $product->shop->deliveryCompanys;
                                    @endphp
                                    <option value="">선택</option>
                                    @foreach($deliverys as $delivery)
                                        <option @if($product->shop_delivery_id==$delivery->pivot->id) selected
                                                @endif value="{{$delivery->pivot->id}}">{{$delivery->name}}
                                            ({{number_format($delivery->pivot->delivery_fare)}})
                                        </option>
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
                    <div id="재고정보Section" class="panel-body panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group">
                            <label class ="control-label col-sm-2">현재 재고</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="stock" data-valid-name="현재 재고" value="{{ $product->getStock() }}" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">품절 알림 재고</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="alert_stock" data-valid-name="품절 알림 재고" value="{{ $product->alert_stock }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">최소 구매 수량</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="min_buy_count" data-valid-name="최소 구매 수량" value="{{ $product->min_buy_count }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">최대 구매 수량</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="max_buy_count" data-valid-name="최대 구매 수량" value="{{ $product->max_buy_count }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">과세 유형</label>
                            <div class="col-sm-9">
                                @foreach (Product::getTaxTypes() as $key => $type)
                                    <label class="radio-inline">
                                        <input type="radio" name="tax_type" value="{{ $key }}"
                                           @if ($product->tax_type == $key) checked @endif>{{ $type }}
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
                                <option-table-component :options="{{ json_encode($options) }}"
                                                        :option-items="{{ json_encode($optionItems) }}"
                                                        product-id="{{$product->id}}"
                                                        product-option-type="{{$product->option_type}}"></option-table-component>
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
                                <custom-option-table-component :custom-options="{{ json_encode(Request::old('custom_options',$customOptions)) }}"
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
                    <div id="추가정보Section" class="panel-body panel-collapse collapse in" role="tabpanel" aria-expanded="false">
                        <div class="form-group">
                            <label class ="control-label col-sm-2">상품 정보 추가 </label>
                            <div class="col-sm-9">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addProductInfo()"><i class="xi-plus"></i> 항목 추가</button>
                                <table class="table table-striped" id="productInfoTable">
                                    <thead>
                                    <tr>
                                        <th>항목</th>
                                        <th>정보</th>
                                        <th>삭제</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr id="productInfoTr0">
                                        <td>상품번호</td>
                                        <td>위 상품코드로 자동 기입</td>
                                        <td></td>
                                    </tr>
                                    @php
                                        $detail_info= (array) json_decode($product->detail_info);
                                        $keys=array_keys($detail_info);
                                    @endphp
                                    @foreach($detail_info as $key=>$val)
                                        <tr id="productInfoTr{{array_search($key,$keys)}}">
                                            <td><input type='text' class="form-control" name='infoKeys[]' value="{{$key}}"></td>
                                            <td><input type='text' class="form-control" name='infoValues[]' value="{{$val}}"></td>
                                            <td>
                                                <button onclick="removeProductInfo({{array_search($key,$keys)}})"
                                                        class="btn btn-default btn-sm">삭제
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">사진업로드 </label>
                            <div class="col-sm-9">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addImage()"><i class="xi-plus"></i> 이미지 추가</button>
                                @for($i=1; $i<=10; $i++)
                                    <div class="col-lg-12 form-image @if($product->images->count()>=$i) open @endif" id="form-image-{{$i}}">
                                        {{ uio('formImage',
                                            ['label'=>($i===1) ? '메인이미지' :'추가이미지 #'.($i-1),
                                            'name'=>'images[]',
                                            'value'=>($product->images->get($i-1))?['path'=>$product->images->get($i-1)->url()]:[],
                                            'id'=>'image'.$i,
                                            'maxSize'=>\Xpressengine\Plugins\XeroCommerce\Models\Product::IMG_MAXSIZE
                                        ]) }}
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">상품 소개</label>
                            <div class="col-sm-9">
                                {!! editor(Plugin::getId(), [
                                   'content' => $product->description,
                                   'contentDomName' => 'description',
                                 ]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-2">태그등록 </label>
                            <div class="col-sm-9">
                                {!! uio('uiobject/xero_commerce@tag', [
                                    'tags' => $product->tags->toArray()
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
        <select name="state_display">
            @foreach (Product::getDisplayStates() as $key => $type)
                <option value="{{ $key }}" @if ($product->state_display == $key) selected @endif>{{ $type }}</option>
            @endforeach
        </select>
        <label>거래 여부</label>
        <select name="state_deal">
            @foreach (Product::getDealStates() as $key => $type)
                <option value="{{ $key }}" @if ($product->state_deal == $key) selected @endif>{{ $type }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-lg">등록</button>
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
    var editImages = {
        edit: function (i) {
            $("#shownImage" + i).addClass('hide').removeClass('show');
            $("#editImage" + i).addClass('show').removeClass('hide');
            $("#shownImage" + i + " input").prop('disabled', true)
            $("#editImage" + i + " input").prop('disabled', false)
        },
        reset: function (i) {
            $("#editImage" + i).addClass('hide').removeClass('show');
            $("#shownImage" + i).addClass('show').removeClass('hide');
            $("#shownImage" + i + " input").prop('disabled', false)
            $("#editImage" + i + " input").prop('disabled', true)
        }
    }
    $(function () {
        $(".editImages input").prop('disabled', true)
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
            alert('메인 페이지를 포함 최대 10개까지 업로드할 수 있습니다.');
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
            $("[name=delivery_id]").val("");
            $("[name=delivery_id] option").not(':selected').remove();
            $.ajax({
                url: '{{route('xero_commerce::setting.config.shop.delivery',['shop'=>''])}}/' + $("[name=shop_id]").val(),
            }).done(function(res) {
                $.each(res, function (k, v){
                    $("[name=delivery_id]").append('<option value="' + v.pivot.id + '">' + v.name + '(' + Number(v.pivot.delivery_fare).toLocaleString() + ')</option>');
                })
            }).fail(function(res) {

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
    .form-image{
        display: none;
    }

    .form-image.open{
        display: block;
    }
    .control-label{
        text-align: right;
    }
    .actually-not-required:after {
        content: none;
    }
</style>
