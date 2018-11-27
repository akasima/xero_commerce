@section('page_title')
    <h2>입점몰 목록</h2>
@endsection

<form method="get" action="{{ route('xero_commerce::setting.config.shop.index') }}">
    <div class="row">
        <div class="col-sm-3">

            <div class="input-group">
                <span class="input-group-btn">
                            <button class="btn btn-default" type="submit">검색</button>
                          </span>
            </div>
        </div>
        <div>
            <a href="{{ route('xero_commerce::setting.config.shop.create') }}" class="xe-btn">새로 등록</a>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>로고</th>
                            <th>한글명</th>
                            <th>id</th>
                            <th>형태</th>
                            <th>판매상태</th>
                            <th>등록일</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($shops as $shop)
                            <tr>
                                <td>
                                    <img src="{!! $shop->logo !!}" alt="">
                                </td>
                                <td><a href="{{ route('xero_commerce::setting.config.shop.show', ['shopId' => $shop->id]) }}"><span>{{ $shop->shop_name }}</span></a></td>
                                <td><a href="{{ route('xero_commerce::setting.config.shop.show', ['shopId' => $shop->id]) }}"><span>{{ $shop->shop_eng_name }}</span></a></td>
                                <td>{{ $shop->getShopTypes()[$shop->shop_type] }}</td>
                                <td></td>
                                <td>{{$shop->created_at->toDateString()}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
