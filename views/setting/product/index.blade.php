@section('page_title')
    <h2>상품 목록</h2>
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-body">
                    @foreach ($products as $product)
                        <li>
                            <a href="{{ route('xero_store::setting.product.show', ['productId' => $product->id]) }}">
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
