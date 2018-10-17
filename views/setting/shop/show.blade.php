@section('page_title')
    <h2>입점몰 수정</h2>
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-body">
                    <div class="form-group">
                        <h4>입점몰 이름</h4>
                        {{ $shop->shop_name }}
                    </div>
                    <div>
                        <h4>배송 정보</h4>
                        {!! $shop->delivery_info !!}
                    </div>
                    <div>
                        <h4>반품/교환 정보</h4>
                        {!! $shop->as_info !!}
                    </div>
                </div>
            </div>

            <a href="{{ route('xero_commerce::setting.config.shop.edit', ['shopId' => $shop->id]) }}" class="xe-btn">수정</a>

            <form method="post" action="{{ route('xero_commerce::setting.config.shop.remove', ['shopId' => $shop->id]) }}">
                {{ csrf_field() }}
                <button type="submit" class="xe-btn xe-btn-danger">삭제</button>
            </form>
        </div>

        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    담당자
                </div>

                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>
