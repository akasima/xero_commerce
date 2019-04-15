{{--@deprecated since ver 1.1.4--}}
{{--<div class="xe-col-sm-12">--}}
    {{--<div class="panel">--}}
        {{--<div class="panel-body">--}}
            {{--<div class="xero-settings-control-float">--}}
                {{--<input type="text">--}}
                {{--<button type="submit" class="btn btn-primary btn-lg">검색</button>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
<div class="xe-col-sm-12">
    <div class="panel">
        <div class="panel-body">
            <div style="width:80%; margin:0 auto; border:2px #ddd solid">
                <table class="table">
                    <thead>
                    <tr>
                        <th style="width:250px">상품명</th>
                        <th>본문</th>
                        <th style="width:100px">글쓴이</th>
                        <th style="width:150px">날짜</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(count($list)==0)
                            <tr>
                                <td colspan="4" style="text-align: center">
                                    조회할 내용이 없습니다.
                                </td>
                            </tr>
                        @endif
                        @include('xero_commerce::views.setting.communication.'.$type.'_row', compact('list'))
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<style>
    table thead tr th{
        text-align: center;
    }
    .date{
        cursor:pointer;
    }
</style>
<script>
    $(function(){
        $(".date").click(function(){
            toggle();
        });
    });
    function toggle()
    {
        $(".format_date").toggleClass("hide");
        $(".real_date").toggleClass("hide");
    }
</script>
