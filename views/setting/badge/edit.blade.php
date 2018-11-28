@section('page_title')
<h2>배지 수정</h2>
@endsection

<form action="{{route('xero_commerce::setting.badge.update', ['badge'=>$badge->id])}}" method="post">
    {{csrf_field()}}
    <div class="panel">
        <div class="panel-body">
            {{uio('formText', [
            'name'=>'name',
            'label'=> '배지 이름',
            'placeholder'=>'배지 이름',
            'value'=>$badge->name
            ])}}
            {{uio('formText', [
            'name'=>'eng_name',
            'label'=> '배지 영어 이름',
            'placeholder'=>'배지 영어 이름',
            'value'=>$badge->eng_name
            ])}}
        </div>
        <div class="panel-footer text-right">
                <button class="btn btn-primary btn-lg">저장</button>
        </div>
    </div>
</form>
