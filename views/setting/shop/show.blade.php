@section('page_title')
    <h2>입점몰 정보</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}

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
                        </tr>
                        <tr>
                            <th>입점몰 ID</th>
                            <td>{{ $shop->shop_eng_name }}</td>
                        </tr>
                        <tr>
                            <th>입점몰 형태</th>
                            <td>{{ $shop->getShopTypes()[$shop->shop_type] }}</td>
                        </tr>
                        <tr>
                            <th>관리자ID</th>
                            <td>
                                @foreach($shop->users as $user)
                                    {{$user->display_name}} ({{$user->email}}) <br>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                    <div>
                        <div class="panel">
                            <div class="panel-heading">
                                <h4>배송사 정보</h4>
                            </div>
                            <div class="panel-body" id="component-container">
                                <shop-delivery-component
                                    :list="{{json_encode($deliveryCompanys)}}"
                                    :delivery="{{json_encode($shop->deliveryCompanys)}}"
                                    load-url="{{route('xero_commerce::setting.config.shop.delivery', ['shop'=>$shop->id])}}"
                                    add-url="{{route('xero_commerce::setting.config.shop.add.delivery', ['shop'=>$shop->id])}}"
                                    remove-url="{{route('xero_commerce::setting.config.shop.remove.delivery', ['shop'=>$shop->id])}}"></shop-delivery-component>
                                <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
                            </div>
                        </div>
                    </div>
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
