@section('page_title')
    <h3>약관변경</h3>
@endsection
<form action="{{route('xero_commerce::setting.agreement.update',['type'=>$agreement->type])}}" method="post">
    <div style="position: relative;">
        <div style="float: left;">
            <h4>{{$agreement->name}}</h4>
        </div>
        <div style="float: right;" >
            <p>마지막 변경일 : {{$agreement->created_at}}</p>
            <p>마지막 version : {{$agreement->version}}</p>
        </div>
    </div>
    <input type="hidden" name="type" value="{{$agreement->type}}">
    {{csrf_field()}}
    <input class="form-control" type="text" name="name" value="{{$agreement->name}}">
    <textarea class="form-control" name="contents" style="resize: none;height:400px">
    {{$agreement->contents}}
</textarea>
    <button class="btn btn-default">약관저장</button>

</form>
