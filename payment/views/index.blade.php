@section('page_title')
    <h2>결제 관리</h2>
@endsection
<div>
    <form action="{{route('xero_pay::post')}}" method="post">
        {{csrf_field()}}
        <div class="ui-sortable xe-row">
            <div class="xe-col-xs-1" >
                <em class="item-title">결제모드</em>
            </div>
            <div class="xe-col-xs-8" >
                <span class="item-subtext">SERVICE TEST 모드를 설정합니다. (on = 테스트, off = 실서비스)</span>
            </div>
            <div class="xe-col-xs-3" >
                <div class="xe-btn-toggle pull-right">
                    <label>
                        <span class="sr-only">toggle</span>
                        <input type="checkbox" name="test" {{$config->get('test') ? 'checked="checked"' : ''}}>
                        <span class="toggle"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="ui-sortable xe-row">
            <div class="xe-col-xs-1" >
                <em class="item-title">PG사선택</em>
            </div>
            <div class="xe-col-xs-11" >
                @foreach($pg as $id => $class)
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <label>
                                    <input type="radio" name="pg" value="{{ $id }}" {{ $config->get('uses') == $id ? 'checked' : '' }}>
                                    {{ $class::getComponentInfo('name') }}
                                </label>
                            </h3>
                            <p>가능한 결제수단 : {{implode($class::$methods, ", ")}} </p>
                        </div>
                        <div class="panel-body">
                            @foreach($class::configItems() as $key => $readable)
                                <div class="form-group">
                                    <label>
                                        {{ $readable }}
                                        <input type="text" class="form-control" name="{{ $id . '_' . ($key = is_numeric($key) ? $readable : $key) }}" value="{{ array_get($config->get('pg'), $id.'.'.$key) }}">
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    <button class="xe-btn" type="submit">전송</button>
    </form>
</div>
<style>
.ui-sortable .xe-row div {
    border-top: 1px solid #e0e0e0;
    background-color: #f8fafd;
    white-space: nowrap;
}
</style>
