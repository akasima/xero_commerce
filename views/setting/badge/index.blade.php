@section('page_title')
    <h2>배지 관리</h2>
@endsection

<div class="row">
    <div class="col-sm-12">
        <div class="panel-group">
            <div class="panel">
                <div class="panel-heading">
                    <div class="form-group">
                        <table class="xe-table">
                            <thead>
                            <tr>
                                <th>배지 이름</th>
                                <th>배지 영어 이름</th>
                                <th>관리</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($badges as $badge)
                                <tr>
                                    <td>{{ $badge->name }}</td>
                                    <td>{{ $badge->eng_name }}</td>
                                    <td>
                                        <a href="{{ route('xero_commerce::setting.badge.edit', ['id' => $badge->id]) }}" class="xe-btn">수정</a>
                                        <form method="post" action="{{ route('xero_commerce::setting.badge.remove', ['id' => $badge->id]) }}">
                                            {!! csrf_field() !!}
                                            <button type="submit" class="xe-btn xe-btn-danger">삭제</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            <form method="post" action="{{ route('xero_commerce::setting.badge.store') }}">
                                {!! csrf_field() !!}
                                <tr>
                                    <td><input name="name" value="{{ Request::old('name') }}"> </td>
                                    <td><input name="eng_name" value="{{ Request::old('eng_name') }}"></td>
                                    <td><button type="submit" class="xe-btn xe-btn-positive">추가</button></td>
                                </tr>
                            </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
