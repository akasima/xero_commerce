@section('page_title')
    <h2>라벨 관리</h2>
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
                                <th>라벨 이름</th>
                                <th>라벨 영어 이름</th>
                                <th>이미지</th>
                                <th>관리</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($labels as $label)
                                <tr>
                                    <td>{{ $label->name }}</td>
                                    <td>{{ $label->eng_name }}</td>
                                    <td></td>
                                    <td>
                                        <a href="{{ route('xero_commerce::setting.label.edit', ['id' => $label->id]) }}" class="xe-btn">수정</a>
                                        <form method="post" action="{{ route('xero_commerce::setting.label.remove', ['id' => $label->id]) }}">
                                             {!! csrf_field() !!}
                                            <button type="submit" class="xe-btn xe-btn-danger">삭제</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            <form method="post" action="{{ route('xero_commerce::setting.label.store') }}">
                                {!! csrf_field() !!}
                                <tr>
                                    <td><input name="name" value="{{ Request::old('name') }}"> </td>
                                    <td><input name="eng_name" value="{{ Request::old('eng_name') }}"></td>
                                    <td></td>
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
