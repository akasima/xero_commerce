@section('page_title')
    <h2>결제 관리</h2>
@endsection
<div>
    <form action="{{route('xero_pay::post')}}" method="post">
        {{csrf_field()}}
    @foreach($pg as $id => $class)
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <label>
                        <input type="radio" name="pg" value="{{ $id }}" {{ $config->get('uses') == $id ? 'checked' : '' }}>
                        {{ $class::getComponentInfo('name') }}
                    </label>
                </h3>
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
    <button class="xe-btn" type="submit">전송</button>
    </form>
</div>
