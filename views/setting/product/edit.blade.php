<?php
use Xpressengine\Plugins\XeroCommerce\Models\Product;
use Xpressengine\Plugins\XeroCommerce\Plugin;
?>

@section('page_title')
    <h2>상품 수정</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}

<form method="post" action="{{ route('xero_commerce::setting.product.update', ['productId' => $product->id]) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <button type="submit" class="xe-btn xe-btn-success">등록</button>
    <div class="row">
        <div class="col-lg-4">
            <div class="panel">
                <div class="panel-body">

                    {{uio('formText', [
                    'label'=>'상품 코드',
                    'name'=>'product_code',
                    'description'=>'비워두면 자동 기입됩니다',
                    'value'=>$product->product_code
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
                    'value'=>$product->name
                    ])}}

                    {{uio('formText', [
                    'label'=>'url명',
                    'name'=>'newSlug',
                    'description'=>'상품 링크 Url로 등록되는 이름입니다.(비워두면 자동으로 설정 됩니다.)',
                    'value'=>$product->getSlug()
                    ])}}

                    {{uio('formText', [
                    'label'=>'간략 소개',
                    'name'=>'sub_name',
                    'description'=>'상품명 아래에 위치하는 소개말입니다.',
                    'value'=>$product->sub_name
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
                    'value'=>$product->original_price
                    ])}}

                    {{uio('formText', [
                    'label'=>'판매 가격',
                    'name'=>'sell_price',
                    'description'=>'실제로 판매하는 가격입니다.',
                    'value'=>$product->sell_price
                    ])}}

                    {{uio('formText', [
                    'label'=>'할인율',
                    'name'=>'discount_percentage',
                    'description'=>'실제 가격, 판매 가격할인율과 근사하게 표기하고싶은 숫자를 직접 적습니다. 비워두면 실제 계산값을 소숫점 두자리까지 저장합니다.',
                    'value'=>$product->discount_percentage
                    ])}}

                    <div class="form-group">
                        <label>배송사</label>
                        <select name="shop_delivery_id" class="form-control">
                            @php
                                $deliverys = $product->shop->deliveryCompanys;
                            @endphp
                            <option value="">선택</option>
                            @foreach($deliverys as $delivery)
                                <option @if($product->shop_delivery_id==$delivery->pivot->id) selected @endif value="{{$delivery->pivot->id}}">{{$delivery->name}}({{number_format($delivery->pivot->delivery_fare)}})</option>
                            @endforeach
                        </select>
                    </div>
                    <script>
                        $(function(){
                            $("[name=shop_id]").change(function(){
                                $("[name=delivery_id]").val("");
                                $("[name=delivery_id] option").not(':selected').remove();
                                $.ajax({
                                    url: '{{route('xero_commerce::setting.config.shop.delivery',['shop'=>''])}}/'+$("[name=shop_id]").val(),
                                }).done(res=>{
                                    $.each(res,(k,v)=>{
                                        $("[name=delivery_id]").append('<option value="'+v.pivot.id+'">'+v.name+'('+Number(v.pivot.delivery_fare).toLocaleString()+')</option>');
                                    })
                                }).fail(res=>{

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

                    <label>초기재고</label>
                    {{$product->getStock()}}
                    <input type="hidden" name="stock" value="{{$product->getStock()}}">
                    {{uio('formText', [
                    'label'=>'품절 알림 재고',
                    'name'=>'alert_stock',
                    'description'=>'이 이하로 떨어지면 품절알림을 합니다.',
                    'value'=>$product->alert_stock
                    ])}}
                    <input type="checkbox" name="buy_count_not_use" checked> 제한없음 <p></p>
                    {{uio('formText', [
                    'label'=>'최소 구매 수량',
                    'name'=>'min_buy_count',
                    'description'=>'최소로 구매해야하는 수량입니다.',
                    'value'=>$product->min_buy_count
                    ])}}
                    {{uio('formText', [
                    'label'=>'최대 구매 수량',
                    'name'=>'max_buy_count',
                    'description'=>'최대로 구매할 수 있는 수량입니다.',
                    'value'=>$product->max_buy_count
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
                            {{uio('formSelect', [
                            'label'=>'과세 유형',
                            'name'=>'tax_type',
                            'options'=>formatArray(Product::getTaxTypes())
                            ])}}
                        </div>
                        <div class="col-lg-4">
                            {{uio('formSelect', [
                            'label'=>'출력 여부',
                            'name'=>'state_display',
                            'options'=>formatArray(Product::getDisplayStates())
                            ])}}
                        </div>
                        <div class="col-lg-4">
                            {{uio('formSelect', [
                            'label'=>'거래 여부',
                            'name'=>'tax_type',
                            'options'=>formatArray(Product::getDealStates())
                            ])}}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>라벨</label>
                                @foreach ($labels as $label)
                                    <input type="checkbox" name="labels[]" value="{{ $label->id }}" @if (in_array($label->id, $productLabelIds) == true) checked @endif>{{ $label->name }}
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label>뱃지</label>
                            <input type="radio" name="badge_id" value="" @if ($product->badge_id == '') checked @endif>사용 안함
                            @foreach ($badges as $badge)
                                <input type="radio" name="badge_id" value="{{ $badge->id }}" @if ($product->badge_id == $badge->id) checked @endif>{{ $badge->name }}
                            @endforeach
                        </div>
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
                                    <td><button onclick="removeProductInfo({{array_search($key,$keys)}})" class="xe-btn xe-btn-default">삭제</button></td>
                                </tr>
                            @endforeach
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
                    <div class="row">
                        @for($i=1; $i<=10; $i++)
                            <div class="col-lg-4">
                                @if($product->images->count()>=$i)
                                    <div id="shownImage{{$i}}" class="show">
                                        <b style="color:blue; cursor:pointer" href="#" onclick="editImages.edit({{$i}})">수정</b>
                                        <div class="form-group">
                                            <label>사진업로드 #{{$i}}</label> <br>
                                            <img src="{{$product->images->get($i-1)->url}}" alt="" width="300px" height="240px">
                                            <input type="hidden" name="nonEditImage[]" value="{{$product->images->get($i-1)->id}}">
                                        </div>
                                    </div>
                                    <div id="editImage{{$i}}" class="editImages hide">
                                        <b style="color:blue; cursor:pointer" href="#" onclick="editImages.reset({{$i}})">초기화</b>
                                        {{ uio('formImage',
                                      ['label'=>'사진업로드 #'.$i,
                                       'name'=>'editImages[]',
                                       'id'=>'image'.$i,
                                       'description'=> '휴대폰 사용하여 입력하세요',
                                       'disabled'=>true
                                ]) }}
                                    </div>
                                @else
                                    {{ uio('formImage',
                                          ['label'=>'사진업로드 #'.$i,
                                           'name'=>'addImages[]',
                                           'id'=>'image'.$i,
                                           'description'=> '휴대폰 사용하여 입력하세요'
                                    ]) }}
                                @endif
                            </div>
                            @if($i%3 === 0)
                            </div>
                            <div class="row">
                            @endif
                        @endfor
                        <script>
                            var editImages={
                                edit (i) {
                                    $("#shownImage"+i).addClass('hide').removeClass('show');
                                    $("#editImage"+i).addClass('show').removeClass('hide');
                                    $("#shownImage"+i+" input").prop('disabled', true)
                                    $("#editImage"+i+" input").prop('disabled', false)
                                },
                                reset (i) {
                                    $("#editImage"+i).addClass('hide').removeClass('show');
                                    $("#shownImage"+i).addClass('show').removeClass('hide');
                                    $("#shownImage"+i+" input").prop('disabled', false)
                                    $("#editImage"+i+" input").prop('disabled', true)
                                }
                            }
                            $(function(){
                                $(".editImages input").prop('disabled',true)
                            })
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                            설명
                            {!! editor(Plugin::getId(), [
                              'content' => $product->description,
                              'contentDomName' => 'description',
                            ]) !!}
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
    <button type="submit" class="xe-btn xe-btn-success">등록</button>
</form>
