<?php
use Xpressengine\Plugins\XeroCommerce\Models\Product;
?>

@section('page_title')
    <h2>상품 목록</h2>
@endsection

<form method="get" action="{{ route('xero_commerce::setting.product.index') }}">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <dl>
                            <dt>이름</dt>
                            <dd><input type="text" name="product_name" value="{{ Request::get('product_name') }}"></dd>

                            <dt>상품코드</dt>
                            <dd><input type="text" name="product_code" value="{{ Request::get('product_code') }}"></dd>

                            <dt>거래 상태</dt>
                            <dd>
                                <select name="product_deal_state">
                                    <option value="">선택</option>
                                    @foreach (Product::getDealStates() as $value => $state)
                                        <option value="{{ $value }}" @if (Request::get('product_deal_state') == $value) selected @endif>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </dd>

                            <dt>출력 상태</dt>
                            <dd>
                                <select name="product_display_state">
                                    <option value="">선택</option>
                                    @foreach (Product::getDisplayStates() as $value => $state)
                                        <option value="{{ $value }}" @if (Request::get('product_display_state') == $value) selected @endif>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </dd>

                            <dt>과세 유형</dt>
                            <dd>
                                <select name="product_tax_type">
                                    <option value="">선택</option>
                                    @foreach (Product::getTaxTypes() as $value => $state)
                                        <option value="{{ $value }}" @if (Request::get('product_tax_type') == $value) selected @endif>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </dd>
                        </dl>
                    </div>
                <button type="submit" class="xe-btn">검색</button>
                <a href="{{ route('xero_commerce::setting.product.index') }}" class="xe-btn">초기화</a>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <a href="{{ route('xero_commerce::setting.product.create') }}" class="xe-btn">등록</a>
            <div class="panel">
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>썸네일</th>
                            <th>상품코드</th>
                            <th>상품명</th>
                            <th>상품설명</th>
                            <th>재고</th>
                            <th>판매가</th>
                            <th>등록일</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td><img src="{{$product->getThumbnailSrc()}}" alt="" style="width:80px; height:60px;"></td>
                                <td>{{ $product->product_code }}</td>
                                <td><a href="{{ route('xero_commerce::setting.product.show', ['productId' => $product->id]) }}"><span>{{ $product->name }}</span></a></td>
                                <td>{{$product->sub_name}}</td>
                                <td>{{ $product->getStock() }}</td>
                                <td>{{number_format($product->sell_price)}}</td>
                                <td>{{$product->created_at->toDateString()}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
