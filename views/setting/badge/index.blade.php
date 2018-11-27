@section('page_title')
<h2>배지 관리</h2>
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-body table-scrollable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>배지 이름</th>
                                <th>배지 영문 이름</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($badges as $badge)
                            <tr>
                                <td>{{ $badge->name }}</td>
                                <td>{{ $badge->eng_name }}</td>
                                <td class="full">
                                    <form method="post" action="{{ route('xero_commerce::setting.badge.remove', ['id' => $badge->id]) }}">
                                        {!! csrf_field() !!}
                                        <a href="{{ route('xero_commerce::setting.badge.edit', ['badge' => $badge->id]) }}" class="xe-btn">수정</a>
                                        <button type="submit" class="xe-btn xe-btn-sm xe-btn-danger">삭제</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel">
                <div class="panel-heading">
                    <h3>배지 추가</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="{{ route('xero_commerce::setting.badge.store') }}">
                        {!! csrf_field() !!}
                        <div class="form-group">
                            <label class="control-label col-xs-4 col-md-2">배지 이름</label>
                            <div class=" col-md-8">
                                <input name="name" value="{{ Request::old('name') }}" class="form-control" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-4 col-md-2">배지 ID</label>
                            <div class="col-md-8">
                                <input name="eng_name" value="{{ Request::old('eng_name') }}" class="form-control" />
                            </div>
                        </div>
                    </form>
                </div>
                <div class="panel-footer text-right">
                    <button type="submit" class="btn btn-primary btn-lg">추가</button>
                </div>
            </div>
        </div>
    </div>
</div>
