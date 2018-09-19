@section('page_title')
    <h2>입점몰 추가</h2>
@endsection

<form method="post" action="{{ route('xero_commerce::setting.config.shop.store') }}">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="form-group">
                            입점몰 이름
                            <input type="text" name="shop_name" value="{{ Request::old('shop_name') }}">
                        </div>

                        <div class="form-group">
                            입점몰 영어 이름
                            <input type="text" name="shop_eng_name" value="{{ Request::old('shop_end_name') }}">
                        </div>

                        <div class="form-group">
                            입점몰 형태
                            <select name="shop_type">
                                <option value="">선택</option>
                                @foreach ($shopTypes as $value => $type)
                                    <option value="{{ $value }}" @if (Request::old('shop_type') == $value) selected="selected" @endif>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            user id
                            <input type="text" name="user_id" value="{{ Request::old('user_id') }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="xe-btn xe-btn-success">등록</button>
</form>
