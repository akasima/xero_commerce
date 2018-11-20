@section('page_title')
    <h2>배지 수정</h2>
@endsection
<form action="{{route('xero_commerce::setting.badge.update', ['badge'=>$badge->id])}}" method="post">
    {{csrf_field()}}
    {{uio('formText', [
        'name'=>'name',
        'placeholder'=>'배지 이름',
        'value'=>$badge->name
    ])}}
    {{uio('formText', [
        'name'=>'eng_name',
        'placeholder'=>'배지 영어 이름',
        'value'=>$badge->eng_name
    ])}}
    <button class="xe-btn xe-btn-black">저장</button>

</form>
