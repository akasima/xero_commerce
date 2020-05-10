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
                            <td>{{ $product->slug }}</td>
                            <th>할인율</th>
                            <td>{{$product->discount_percentage}} %</td>
                            <th>최소 구매 수량</th>
                            <td>{{ number_format($product->min_buy_count ) }}</td>
                        </tr>
                        <tr>
                            <th>간략소개</th>
                            <td>{{$product->sub_name}}</td>
                            <th>배송사</th>
                            <td>{{$product->shopCarrier->carrier->name}}
                                ({{number_format($product->shopCarrier->fare)}})
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
                                    <td>{{ $optionItem['additional_price'] }}</td>
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

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <h3>추가옵션</h3>
                </div>

                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>옵션타입</th>
                            <th>옵션명</th>
                            <th>옵션설정</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customOptions as $option)
                            <tr>
                                <td>{{ $customOptionTypes[$option['type']] }}</td>
                                <td>{{ $option['name'] }}</td>
                                <td>
                                    {{ $option['is_required'] ? '필수' : '필수아님'  }}
                                    {{ $option['settings'] }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

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
