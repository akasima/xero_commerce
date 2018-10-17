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
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                            상품 코드 (비워두면 timestamp)
                            <input type="text" name="product_code" value="{{ $product->product_code }}">
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
                        @for($i=1; $i<=10; $i++)
                            @if($product->images->count()>=$i)
                                <div id="shownImage{{$i}}" class="show">
                                    <b style="color:blue; cursor:pointer" href="#" onclick="editImages.edit({{$i}})">수정</b>
                                    <div class="form-group">
                                        <label>사진업로드 #{{$i}}</label> <br>
                                        <img src="{{$product->images->get($i-1)->url}}" alt="">
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
