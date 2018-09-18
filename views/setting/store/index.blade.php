@section('page_title')
    <h2>입점몰 목록</h2>
@endsection

<form method="get" action="{{ route('xero_commerce::setting.config.store.index') }}">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <dl>
                            <dt>이름</dt>
                            <dd><input type="text" name="store_name"></dd>
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
            <a href="{{ route('xero_commerce::setting.config.store.create') }}" class="xe-btn">등록</a>
            <div class="panel">
                <div class="panel-body">
                    @foreach ($stores as $store)
                        <li>
                            <div>
                                <span>{{ $store->store_name }}</span>
                            </div>
                        </li>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
