@section('page_title')
    <h2>입점몰 정보</h2>
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <h2>{{$shop->shop_name}}</h2>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-1">
                            <a href="{{ route('xero_commerce::setting.config.shop.edit', ['shopId' => $shop->id]) }}" class="xe-btn xe-btn-block">수정</a>
                        </div>
                        <div class="col-lg-1">

                            <form method="post" action="{{ route('xero_commerce::setting.config.shop.remove', ['shopId' => $shop->id]) }}">
                                {{ csrf_field() }}
                                <button type="submit" class="xe-btn xe-btn-danger xe-btn-block">삭제</button>
                            </form>
                        </div>
                    </div>
                    <table class="table">
                        <tr>
                            <th>입점몰 이름</th>
                            <td>{{ $shop->shop_name }}</td>
                            <th>배송회사</th>
                            <td>{{ $shop->getDefaultDeliveryCompany()->name }}</td>
                        </tr>
                        <tr>
                            <th>입점몰 영어 이름</th>
                            <td>{{ $shop->shop_eng_name }}</td>
                            <th>배송비</th>
                            <td>{{ number_format($shop->getDefaultDeliveryCompany()->pivot->delivery_fare) }}</td>
                        </tr>
                        <tr>
                            <th>입점몰 형태</th>
                            <td>{{ $shop->getShopTypes()[$shop->shop_type] }}</td>
                            <th>관리자ID</th>
                            <td>{{ $shop->user_id}}</td>
                        </tr>
                    </table>
                    <div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4>배송 정보</h4>
                            </div>
                            <div class="panel-body">
                                {!! $shop->delivery_info !!}
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4>반품/교환 정보</h4>
                            </div>
                            <div class="panel-body">
                                {!! $shop->as_info !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .table tr th {
        background-color: #eee;
        width:200px
    }
</style>
