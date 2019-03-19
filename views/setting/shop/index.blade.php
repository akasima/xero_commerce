{{--@deprecated since ver 1.1.4--}}
@section('page_title')
<h2>입점몰 목록</h2>
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-body table-scrollable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>로고</th>
                                <th>한글명</th>
                                <th>영문명</th>
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
                                <td class="nowrap"><a href="{{ route('xero_commerce::setting.config.shop.show', ['shopId' => $shop->id]) }}"><span>{{ $shop->shop_name }}</span></a></td>
                                <td class="nowrap"><a href="{{ route('xero_commerce::setting.config.shop.show', ['shopId' => $shop->id]) }}"><span>{{ $shop->shop_eng_name }}</span></a></td>
                                <td class="nowrap">{{ $shop->getShopTypes()[$shop->shop_type] }}</td>
                                <td class="nowrap"></td>
                                <td class="nowrap">{{$shop->created_at->toDateString()}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="xero-settings-control-float">
    <a href="{{ route('xero_commerce::setting.config.shop.create') }}" class="btn btn-primary btn-lg">입점몰 추가</a>
</div>
