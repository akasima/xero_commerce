@section('page_title')
    <h2>입점몰 목록</h2>
@endsection

<form method="get" action="{{ route('xero_commerce::setting.config.shop.index') }}">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <dl>
                            <dt>이름</dt>
                            <dd><input type="text" name="shop_name"></dd>
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
            <a href="{{ route('xero_commerce::setting.config.shop.create') }}" class="xe-btn">등록</a>
            <div class="panel">
                <div class="panel-body">
                    @foreach ($shops as $shop)
                        <li>
                            <div>
                                <a href="{{ route('xero_commerce::setting.config.shop.show', ['shopId' => $shop->id]) }}"><span>{{ $shop->shop_name }}</span></a>
                            </div>
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
