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
                    @foreach ($products as $product)
                        <li>
                            <a href="{{ route('xero_commerce::setting.product.show', ['productId' => $product->id]) }}">
                                <div>
                                    <span>{{ $product->name }}</span>
                                    <span>{{ $product->price }}</span>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
