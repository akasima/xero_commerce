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
                            <dd><input type="text" name="name"></dd>
                        </dl>
                    </div>
                <button type="submit" class="xe-btn">검색</button>
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
