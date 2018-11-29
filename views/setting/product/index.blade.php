<?php
use Xpressengine\Plugins\XeroCommerce\Models\Product;
?>

@section('page_title')
<h2>상품 목록</h2>
@endsection

<form method="get" class="form-horizontal" action="{{ route('xero_commerce::setting.product.index') }}">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>상품 이름</label>
                                <input type="text" name="product_name" class="form-control" value="{{ Request::get('product_name') }}">
                            </div>
                            <div class="col-md-4">
                                <label>상품 코드</label>
                                <input type="text" name="product_code" class="form-control" value="{{ Request::get('product_code') }}">
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>판매 상태</label>
                                <select name="product_deal_state" class="form-control">
                                    <option value="">선택</option>
                                    @foreach (Product::getDealStates() as $value => $state)
                                    <option value="{{ $value }}" @if (Request::get('product_deal_state') == $value) selected @endif>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>상품 노출</label>
                                <select name="product_display_state" class="form-control">
                                    <option value="">선택</option>
                                    @foreach (Product::getDisplayStates() as $value => $state)
                                    <option value="{{ $value }}" @if (Request::get('product_display_state') == $value) selected @endif>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row form-group">
                            <div class="col-md-4">
                                <label>과세 유형</label>
                                <select name="product_tax_type" class="form-control">
                                    <option value="">선택</option>
                                    @foreach (Product::getTaxTypes() as $value => $state)
                                    <option value="{{ $value }}" @if (Request::get('product_tax_type') == $value) selected @endif>{{ $state }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                            <div class="text-right">
                                <a href="{{ route('xero_commerce::setting.product.index') }}" class="xe-btn xe-btn-link">초기화</a>
                                <button type="submit" class="xe-btn xe-btn-primary">검색</button>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="xero-settings-control-float">
                <a href="{{ route('xero_commerce::setting.product.create') }}" class="xe-btn xe-btn-primary xe-btn-lg">상품 등록</a>
            </div>
            <div class="panel">
                <div class="panel-body table-scrollable">
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
                                <td class="nowrap">{{ $product->product_code }}</td>
                                <td class="full"><a href="{{ route('xero_commerce::setting.product.show', ['productId' => $product->id]) }}"><span>{{ $product->name }}</span></a></td>
                                <td class="full">{{$product->sub_name}}</td>
                                <td class="nowrap">{{ $product->getStock() }}</td>
                                <td class="nowrap">{{ number_format($product->sell_price)}}</td>
                                <td class="nowrap">{{$product->created_at->toDateString()}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
