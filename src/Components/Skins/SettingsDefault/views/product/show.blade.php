@section('page_title')
    <h2>상품 수정</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}

<div class="clearfix" style="margin-bottom: 25px;">
    <div class="pull-left">
        <a href="{{ route('xero_commerce::setting.product.index') }}" class="btn btn-link"><i class="xi-arrow-left"></i> 목록으로 돌아가기</a>
    </div>
    <div class="pull-right">
        <form method="post" action="{{ route('xero_commerce::setting.product.remove', ['productId' => $product->id]) }}">
                {{ csrf_field() }}
            <a href="{{ route('xero_commerce::setting.product.edit', ['productId' => $product->id]) }}" class="btn btn-default">수정</a>
            <button type="submit" class="btn btn-danger">삭제</button>
        </form>
    </div>
</div>

<div id="component-container" class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-body table-responsive">
                    <table class="table">
                        <tr>
                            <th>상품코드</th>
                            <td>{{ $product->product_code }}</td>
                            <th>정상가격</th>
                            <td>{{ number_format($product->original_price ) }}</td>
                            <th>현 재고</th>
                            <td>{{ number_format($product->getStock() ) }}</td>
                        </tr>
                        <tr>
                            <th>상품명</th>
                            <td>{{ $product->name }}</td>
                            <th>판매가격</th>
                            <td>{{ number_format($product->sell_price)  }}</td>
                            <th>품절 알림 재고</th>
                            <td>{{ number_format($product->alert_stock ) }}</td>
                        </tr>
                        <tr>
                            <th>url명</th>
                            <td>{{ $product->getSlug() }}</td>
                            <th>할인율</th>
                            <td>{{$product->discount_percentage}} %</td>
                            <th>최소 구매 수량</th>
                            <td>{{ number_format($product->min_buy_count ) }}</td>
                        </tr>
                        <tr>
                            <th>간략소개</th>
                            <td>{{$product->sub_name}}</td>
                            <th>배송사</th>
                            <td>{{$product->delivery->company->name}}
                                ({{number_format($product->delivery->delivery_fare)}})
                            </td>
                            <th>최대 구매 수량</th>
                            <td>{{ number_format($product->max_buy_count ) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <h3>상품정보</h3>
                </div>

                <div class="panel-body table-responsive">
                    <table class="table detail_info">
                        <tr>
                            @php
                                $i =0;
                            @endphp
                            @foreach((array)json_decode($product->detail_info) as $key => $val)
                                <th>
                                    {{$key}}
                                </th>
                                <td>
                                    {{$val}}
                                </td>
                                @if($i%2==1)
                        </tr>
                        <tr>
                            @endif
                            @php
                                $i++;
                            @endphp
                            @endforeach
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <h3>옵션</h3>
                </div>

                <div class="panel-body">
                    <div class="form-group">
                        <label class="control-label col-sm-2">옵션 타입</label>
                        <div class="col-sm-10">
                            <label>{{ $product->getOptionTypeName() }}</label>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>옵션명</th>
                                <th>옵션값</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($options as $option)
                            <tr>
                                <td>{{ $option['name'] }}</td>
                                <td>{{ implode(', ', $option['values']) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>옵션품목명</th>
                                <th>추가 금액</th>
                                <th>현재 재고</th>
                                <th>품절 알림 재고</th>
                                <th>출력 상태</th>
                                <th>판매 상태</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($optionItems as $optionItem)
                                <tr>
                                    <td>{{ $optionItem['name'] }}</td>
                                    <td>{{ $optionItem['addition_price'] }}</td>
                                    <td>{{ $optionItem['stock'] }}</td>
                                    <td>{{ $optionItem['alert_stock'] }}</td>
                                    <td>{{ $optionItem['state_display'] == 1 ? '출력' : '미출력' }}</td>
                                    <td>{{ $optionItem['state_deal'] == 1 ? '판매' : ($optionItem['state_deal'] == 2 ? '일시중단' : '중단') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

		@if($product instanceof \Xpressengine\Plugins\XeroCommerce\Models\Products\BundleProduct)
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <h3>묶음 상품</h3>
                </div>

                <div class="panel-body table-responsive" style="overflow-y: visible">
                    <table class="table detail_info">
                    	<thead>
                            <tr>
                            	<th>상품 코드</th>
                            	<th>상품명</th>
                            	<th>상품 옵션</th>
                            	<th>단가</th>
                            	<th>수량</th>
                            	<th>합계</th>
                            	<th>작업</th>
                            </tr>
                    	</thead>
                    	<tbody>
                    		@foreach ($product->items as $item)
                            <tr>
                            	<td>{{ $item->product->product_code }}</td>
                            	<td>{{ $item->product->name }}</td>
                            	<td>{{ $item->option_values }}</td>
                            	<td>{{ $item->product->sell_price }}</td>
                            	<td>{{ $item->quantity }}</td>
                            	<td>{{ $item->product->sell_price * $item->quantity }}</td>
                            	<td></td>
                            </tr>
                    		@endforeach
                            <tr>
                            	<td colspan="7">
                                    <div class="input-group">
                                        <input type="text" id="inputProductSearch" class="form-control" placeholder="상품 검색">
                                        <span class="input-group-btn">
                                        	<button class="btn btn-default" type="button" id="btnProductSearch">검색</button>
                                        </span>
                                    </div><!-- /input-group -->
                                    <ul id="productSearchResult" class="list-group" style="display: none">
                                    </ul>
                            	</td>
                            </tr>
                    	</tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <h3>상품설명</h3>
                </div>

                <div class="panel-body">
                    {!! $product->description !!}
                </div>
            </div>
        </div>

        <div class="panel-group">
            @foreach ($product->tags->toArray() as $tag)
                <span class="xe-badge xe-black">#{{ $tag['word'] }}</span>
            @endforeach
        </div>
    </div>
</div>

@if($product instanceof \Xpressengine\Plugins\XeroCommerce\Models\Products\BundleProduct)
<form id="bundleItemForm" method="post" action="{{ route('xero_commerce::setting.product.bundle.items', ['productId' => $product->id]) }}">
    {{ csrf_field() }}
    <input type="hidden" name="product_id" value="" />
</form>
<style>
li.product-search-result {
    cursor: pointer;
}
</style>
<script>
	$(document).ready(function() {
		var productMap = {};
		$('#btnProductSearch').click(function() {
			var keyword = $('#inputProductSearch').val();
			XE.Request.get('{{ route('xero_commerce:setting.product.search') }}', { 'product_name' : keyword })
			.then(function(res) {
				var products = res.data.products;
				console.log(products);
				var htmlData = '';
				products.map(function(product) {
					htmlData += `<li class="list-group-item product-search-result" data-pid="${product.id}">${product.name}</li>`;
					productMap[product.id] = product;
				});
				$('#productSearchResult').html(htmlData);
				$('#productSearchResult').show();
			});
		});

		$('#productSearchResult').on('click', '.product-search-result', function(e) {
			$('#productSearchResult').hide();
			var id = $(this).data('pid');
			var product = productMap[Number(id)];

			$('#bundleItemForm').find('input[name=product_id]').val(id);
			$('#bundleItemForm').submit();
		});
	});

</script>
@endif
