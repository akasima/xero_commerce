<?php

use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Plugin;

?>

@section('page_title')
    <h2>상품 수정</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->before('plugins/board/assets/js/BoardTags.js')->appendTo('body')->load() }}

<form id="save" method="post" action="{{ route('xero_commerce::setting.product.update', ['productId' => $product->id]) }}"
      enctype="multipart/form-data"
      data-rule="product" data-rule-alert-type="toast">
    {{ csrf_field() }}
    <div class="xe-row">
        <div class="xe-col-sm-8">
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel __xe_section_box">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">상품정보</h3>
                        </div>
                        <div class="pull-right">
                            <a data-toggle="collapse" data-parent="#accordion" data-target="#상품정보Section" href="#collapseTwo" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        </div>
                    </div>
                    <div id="상품정보Section" class="panel-collapse collapse" role="tabpanel" aria-expanded="false" style="height: 0px;">

                        <div class="form-group">
                            <label for="pwd" class ="control-label col-sm-3">상품명</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="name" value="{{ $product->name }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">간략 소개</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="sub_name" value="{{ $product->sub_name }}">
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-3">카테고리</label>
                            <div class="col-sm-8">
                                <category-component :category-items='{{ json_encode($categoryItems) }}'
                                                    get-child-url="{{ route('xero_commerce:setting.product.category.getChild') }}"
                                                    mode="create"
                                                    :selected="{{ json_encode($productCategorys)}}">
                                </category-component>
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-3">라벨</label>
                            <div class="col-sm-8">
                                @foreach ($labels as $label)
                                    <input type="checkbox" name="labels[]" value="{{ $label->id }}"
                                           @if (in_array($label->id, $productLabelIds) == true) checked @endif>{{ $label->name }}
                                @endforeach
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-3">배지</label>
                            <div class="col-sm-8">
                                <input type="radio" name="badge_id" value="" @if ($product->badge_id == '') checked @endif>사용
                                안함
                                @foreach ($badges as $badge)
                                    <input type="radio" name="badge_id" value="{{ $badge->id }}"
                                           @if ($product->badge_id == $badge->id) checked @endif>{{ $badge->name }}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">상품 코드</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="product_code" value="{{ $product->product_code }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">가격정보</h3>
                        </div>
                        <div class="pull-right">
                            <a data-toggle="collapse" data-parent="#accordion" data-target="#가격정보Section" href="#collapseTwo" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        </div>
                    </div>
                    <div id="가격정보Section" class="panel-collapse collapse" role="tabpanel" aria-expanded="false" style="height: 0px;">

                        <div class="form-group">
                            <label class ="control-label col-sm-3">정상 가격</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="original_price" value="{{ $product->original_price }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">판매 가격</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="sell_price" value="{{ $product->sell_price }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">할인율</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="discount_percentage" value="{{ $product->discount_percentage }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">배송정보</h3>
                        </div>
                        <div class="pull-right">
                            <a data-toggle="collapse" data-parent="#accordion" data-target="#배송정보Section" href="#collapseTwo" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        </div>
                    </div>
                    <div id="배송정보Section" class="panel-collapse collapse" role="tabpanel" aria-expanded="false" style="height: 0px;">
                        <div class="form-group">
                            <label class ="control-label col-sm-3">배송사</label>
                            <div class="col-sm-8">
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
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">재고정보</h3>
                        </div>
                        <div class="pull-right">
                            <a data-toggle="collapse" data-parent="#accordion" data-target="#재고정보Section" href="#collapseTwo" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        </div>
                    </div>
                    <div id="재고정보Section" class="panel-collapse collapse" role="tabpanel" aria-expanded="false" style="height: 0px;">
                        <div class="form-group">
                            <label class ="control-label col-sm-3">초기 재고</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="stock" value="{{ $product->getStock() }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">품절 알림 재고</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="alert_stock" value="{{ $product->alert_stock }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">최소 구매 수량</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="min_buy_count" value="{{ $product->min_buy_count }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">최대 구매 수량</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="max_buy_count" value="{{ $product->max_buy_count }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">과세 유형</label>
                            <div class="col-sm-8">
                                @foreach (Product::getTaxTypes() as $key => $type)
                                    <input type="radio" name="tax_type" value="{{ $key }}"
                                           @if ($product->tax_type == $key) checked @endif>{{ $type }}
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel __xe_section_box">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <h3 class="panel-title">추가정보</h3>
                        </div>
                        <div class="pull-right">
                            <a data-toggle="collapse" data-parent="#accordion" data-target="#추가정보Section" href="#collapseTwo" class="btn-link panel-toggle collapsed" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
                        </div>
                    </div>
                    <div id="추가정보Section" class="panel-collapse collapse" role="tabpanel" aria-expanded="false" style="height: 0px;">
                        <div class="form-group">
                            <label class ="control-label col-sm-3">상품 정보 추가 </label>
                            <div class="col-sm-8">
                                <h4><i class="xi-plus" style="cursor:pointer" onclick="addProductInfo()"></i></h4>
                                <table class="table" id="productInfoTable">
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
                                    </tr>
                                    @php
                                        $detail_info= (array) json_decode($product->detail_info);
                                        $keys=array_keys($detail_info);
                                    @endphp
                                    @foreach($detail_info as $key=>$val)
                                        <tr id="productInfoTr{{array_search($key,$keys)}}">
                                            <td><input type='text' name='infoKeys[]' value="{{$key}}"></td>
                                            <td><input type='text' name='infoValues[]' value="{{$val}}"></td>
                                            <td>
                                                <button onclick="removeProductInfo({{array_search($key,$keys)}})"
                                                        class="xe-btn xe-btn-default">삭제
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">사진업로드 </label>
                            <div class="col-sm-8">
                                @for($i=1; $i<=10; $i++)
                                    <div class="col-lg-4">
                                        @if($product->images->count()>=$i)
                                            <div id="shownImage{{$i}}" class="show">
                                                <b style="color:blue; cursor:pointer" href="#"
                                                   onclick="editImages.edit({{$i}})">수정</b>
                                                <div class="form-group">
                                                    <label>사진업로드 #{{$i}}</label> <br>
                                                    <img
                                                        src="{{XeMedia::image()->getThumbNail($product->images->get($i-1),'widen','M')->url()}}"
                                                        alt="">
                                                    <input type="hidden" name="nonEditImage[]"
                                                           value="{{$product->images->get($i-1)->id}}">
                                                </div>
                                            </div>
                                            <div id="editImage{{$i}}" class="editImages hide">
                                                <b style="color:blue; cursor:pointer" href="#"
                                                   onclick="editImages.reset({{$i}})">초기화</b>
                                                {{ uio('formImage',
                                              ['label'=>'사진업로드 #'.$i,
                                               'name'=>'editImages[]',
                                               'id'=>'image'.$i,
                                               'maxSize'=>\Xpressengine\Plugins\XeroCommerce\Models\Product::IMG_MAXSIZE,
                                               'description'=> '휴대폰 사용하여 입력하세요',
                                               'disabled'=>true
                                        ]) }}
                                            </div>
                                        @else
                                            {{ uio('formImage',
                                                  ['label'=>'사진업로드 #'.$i,
                                                   'name'=>'addImages[]',
                                                   'id'=>'image'.$i,
                                                   'maxSize'=>\Xpressengine\Plugins\XeroCommerce\Models\Product::IMG_MAXSIZE,
                                                   'description'=> '휴대폰 사용하여 입력하세요'
                                            ]) }}
                                        @endif
                                    </div>
                                    @if($i%3 === 0)
                            </div>
                            <div class="row">
                                @endif
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">상세설명 </label>
                            <div class="col-sm-8">
                                {!! editor(Plugin::getId(), [
                                   'content' => $product->description,
                                   'contentDomName' => 'description',
                                 ]) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">태그등록 </label>
                            <div class="col-sm-8">
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
        <button type="submit" class="xe-btn xe-btn-success xe-btn-lg">등록</button>
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
        edit(i) {
            $("#shownImage" + i).addClass('hide').removeClass('show');
            $("#editImage" + i).addClass('show').removeClass('hide');
            $("#shownImage" + i + " input").prop('disabled', true)
            $("#editImage" + i + " input").prop('disabled', false)
        },
        reset(i) {
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
        tr += "<td><input type='text' name='infoKeys[]'></td>"
        tr += "<td><input type='text' name='infoValues[]'></td>"
        tr += "<td><button onclick='removeProductInfo(" + id + ")' class='xe-btn xe-btn-default'>삭제</button></td>"
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
</script>
<script>
    $(function () {
        $("[name=shop_id]").change(function () {
            $("[name=delivery_id]").val("");
            $("[name=delivery_id] option").not(':selected').remove();
            $.ajax({
                url: '{{route('xero_commerce::setting.config.shop.delivery',['shop'=>''])}}/' + $("[name=shop_id]").val(),
            }).done(res => {
                $.each(res, (k, v) => {
                    $("[name=delivery_id]").append('<option value="' + v.pivot.id + '">' + v.name + '(' + Number(v.pivot.delivery_fare).toLocaleString() + ')</option>');
                })
            }).fail(res => {

            })
        })
    })
</script>

<style>
    .col-lg-4:nth-child(3n+1) {
        clear: both;
    }
</style>
