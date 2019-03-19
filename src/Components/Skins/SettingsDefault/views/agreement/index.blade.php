
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    @foreach(\Xpressengine\Plugins\XeroCommerce\Models\Agreement::groupBy('type')->select(DB::raw('*, max(version) as version'))->orderBy('version','desc')->get() as $agreement)
    <div class="panel __xe_section_box">
        <div class="panel-heading" data-toggle="collapse" data-target="#상품정보Section" aria-expanded="false">
            <a data-toggle="collapse" data-target="#{{$agreement->type}}" class="btn-link panel-toggle" aria-expanded="false"><i class="xi-angle-down"></i><i class="xi-angle-up"></i><span class="sr-only">메뉴닫기</span></a>
            <h3 class="panel-title">{{$agreement->name}} (V. {{$agreement->version}}) - <a href="{{route('xero_commerce::setting.agreement.edit',['type'=>$agreement->type])}}">수정</a></h3>
        </div>
        <div id="{{$agreement->type}}" class="panel-collapse collapse in" role="tabpanel" aria-expanded="false">
            {{$agreement->contents}}
        </div>
    </div>
    @endforeach
</div>
