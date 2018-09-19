<?php
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
?>

@section('page_title')
    <h2>입점몰 수정</h2>
@endsection

<form method="post" action="{{ route('xero_commerce::setting.config.shop.update', ['shopId' => $shop->id]) }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                            입점몰 이름
                            <input type="text" name="shop_name" value="{{ $shop->shop_name }}">
                        </div>

                        <div class="form-group">
                            입점몰 영어 이름
                            <input type="text" name="shop_eng_name" value="{{ $shop->shop_eng_name }}">
                        </div>

                        {{--기본 입점몰이면 형태를 못 바꾸도록--}}
                        @if ($shop->shop_type != Shop::TYPE_BASIC_SHOP)
                        <div class="form-group">
                            입점몰 형태
                            <select name="shop_type">
                                <option value="">선택</option>
                                @foreach ($shopTypes as $value => $type)
                                    <option value="{{ $value }}" @if($shop->shop_type == $value) selected="selected" @endif>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="xe-btn xe-btn-success">수정</button>
</form>
