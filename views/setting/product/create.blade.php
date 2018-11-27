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
                                <input type="text" class="form-control" name="name" value="{{ Request::old('name') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">간략 소개</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="sub_name" value="{{ Request::old('sub_name') }}">
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-3">카테고리</label>
                            <div class="col-sm-8">
                                <category-component :category-items='{{ json_encode($categoryItems) }}'
                                                    get-child-url="{{ route('xero_commerce:setting.product.category.getChild') }}"
                                                    mode="create">
                                </category-component>
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-3">라벨</label>
                            <div class="col-sm-8">
                                @foreach ($labels as $label)
                                    <input type="checkbox" name="labels[]" value="{{ $label->id }}" @if (in_array($label->id, Request::old('labels', [])) == true) checked @endif>{{ $label->name }}
                                @endforeach
                            </div>
                        </div>
                        <div id="component-container" class="form-group">
                            <label class ="control-label col-sm-3">배지</label>
                            <div class="col-sm-8">
                                <input type="radio" name="badge_id" value="" checked>사용 안함
                                @foreach ($badges as $badge)
                                    <input type="radio" name="badge_id" value="{{ $badge->id }}" @if (Request::old('badge_id') == $badge->id) checked @endif>{{ $badge->name }}
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">상품 코드</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="product_code" value="{{ Request::old('product_code') }}">
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
                                <input type="text" class="form-control" name="original_price" value="{{ Request::old('original_price') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">판매 가격</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="sell_price" value="{{ Request::old('sell_price') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">할인율</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="discount_percentage" value="{{ Request::old('discount_percentage') }}">
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
                                        //TODO 입점몰이 여러개일 때 처리
                                        $deliverys = $shops[0]->deliveryCompanys;
                                    @endphp
                                    <option value="">선택</option>
                                    @foreach($deliverys as $delivery)
                                        <option value="{{$delivery->pivot->id}}" @if (Request::old('shop_delivery_id') == $delivery->pivot->id) selected @endif>{{$delivery->name}}({{number_format($delivery->pivot->delivery_fare)}})</option>
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
                                <input type="text" class="form-control" name="stock" value="{{ Request::old('stock') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">품절 알림 재고</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="alert_stock" value="{{ Request::old('alert_stock') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">최소 구매 수량</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="min_buy_count" value="{{ Request::old('min_buy_count') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">최대 구매 수량</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="max_buy_count" value="{{ Request::old('max_buy_count') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">과세 유형</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="tax_type">
                                    @foreach (Product::getTaxTypes() as $key => $type)
                                        <option value="{{ $key }}" @if (Request::old('tax_type') == $key) selected @endif>{{ $type }}</option>
                                    @endforeach
                                </select>
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
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">사진업로드 </label>
                            <div class="col-sm-8">
                                @for($i=1; $i<=10; $i++)
                                    <div class="col-lg-4">
                                        {{ uio('formImage',
                                              ['label'=>'사진업로드 #'.$i,
                                               'name'=>'images[]',
                                               'id'=>'image'.$i,
                                               'maxSize'=>\Xpressengine\Plugins\XeroCommerce\Models\Product::IMG_MAXSIZE
                                        ]) }}
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <label class ="control-label col-sm-3">상세설명 </label>
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
        <button type="submit" class="xe-btn xe-btn-success">등록</button>
    </div>

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
        tr += "<td><input type='text' name='infoKeys[]'></td>"
        tr += "<td><input type='text' name='infoValues[]'></td>"
        tr += "<td><button onclick='removeProductInfo(" + id + ")' class='xe-btn xe-btn-default'>삭제</button></td>"
        $("#productInfoTable tbody").append(tr)
    }

    function removeProductInfo(id) {
        $("#productInfoTable tbody tr#productInfoTr" + id).remove()
    }
</script>

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
<style>
    .col-lg-4:nth-child(3n+1) {
        clear: both;
    }
    .control-label{
        text-align: center;
    }
    .panel-collapse{
        padding:10px;
    }
</style>
