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
                            회사명
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="xe-btn xe-btn-success">등록</button>
</form>
