{{--@deprecated since ver 1.1.4--}}
@section('page_title')
<h2>배지 관리</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/colorpicker.min.js')))->appendTo('body')->load() }}
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
                                <th>배경색</th>
                                <th>글자색</th>
                                <th>미리보기</th>
                                <th>관리</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($badges as $badge)
                            <tr>
                                <td>{{ $badge->name }}</td>
                                <td>{{ $badge->eng_name }}</td>
                                <td>
                                    @if($badge->background_color)
                                        <div style="width:20px;height:20px;background-color:{{ $badge->background_color }}; border:2px black solid"></div>
                                    @endif
                                </td>
                                <td>
                                    @if($badge->text_color)
                                        <div style="width:20px;height:20px;background-color:{{ $badge->text_color }}; border:2px black solid"></div>
                                    @endif
                                </td>
                                <td>
                                    <div class="badge-container">
                                        <div class="badge" @if($badge->background_color)style="background: {{$badge->background_color}};" @endif>
                                            <span id="new" @if($badge->text_color)style="color:{{$badge->text_color}}" @endif>{{$badge->name}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('xero_commerce::setting.badge.remove', ['id' => $badge->id]) }}">
                                        {!! csrf_field() !!}
                                        <a href="{{ route('xero_commerce::setting.badge.edit', ['badge' => $badge->id]) }}" class="btn btn-default btn-sm">수정</a>
                                        <button type="submit" class="btn btn-sm btn-danger">삭제</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel">
                <form method="post" action="{{ route('xero_commerce::setting.badge.store') }}">
                <div class="panel-heading">
                    <h3 class="panel-title">배지 추가</h3>
                </div>
                <div class="panel-body">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label class="control-label col-xs-4 col-md-2">미리보기</label>
                                    <div class=" col-md-8">
                                        <div class="badge-container">
                                            <div class="badge" id="new">
                                                <span>뱃지명</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label col-xs-4 col-md-2">배지 이름</label>
                                    <div class=" col-md-8">
                                        <input name="name" value="{{ Request::old('name')?:'뱃지명' }}" class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label col-xs-4 col-md-2">배지 ID</label>
                                    <div class="col-md-8">
                                        <input name="eng_name" value="{{ Request::old('eng_name')?:'badge' }}" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label col-xs-4 col-md-2">배경색</label>
                                    <div class="col-md-8" style="position:relative">
                                        <div id="background_cp" class="cp-default"></div>
                                        <div id="background_view" style="width:124px;height:124px;position:absolute;top:0px;left:150px;padding:12px;"></div>
                                    </div>
                                    <input class="form-control" name="background_color" type="hidden" id="background" value="#dddddd"/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="control-label col-xs-4 col-md-2">글자색</label>
                                    <div class="col-md-8" style="position:relative">
                                        <div id="text_cp" class="cp-default"></div>
                                        <div id="text_view" style="width:124px;height:124px;position:absolute;top:0px;left:150px;padding:12px;"></div>
                                    </div>
                                    <input class="form-control" name="text_color" type="hidden" id="text" value="#333333"/>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="panel-footer text-right">
                    <button type="submit" class="btn btn-primary btn-lg">추가</button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    $(function () {
        var b_cp = ColorPicker(document.getElementById('background_cp'), function (hex, hsv, rgb) {
            $("#background").val(rgbObjectToString(rgb))
            $("#background_view").css('background',rgbObjectToString(rgb))
            changeLabel()
        })

        b_cp.setRgb({r: parseInt('68', 16), g: parseInt('68', 16), b: parseInt('68', 16)})

        var t_cp = ColorPicker(document.getElementById('text_cp'), function (hex, hsv, rgb) {
            $("#text").val(rgbObjectToString(rgb))
            $("#text_view").css('background',rgbObjectToString(rgb))
            changeLabel()
        })

        t_cp.setRgb({r: parseInt('f5', 16), g: parseInt('f5', 16), b: parseInt('f5', 16)})

        $("input").on("input",function(){
            changeLabel()
        })

        function changeLabel(){
            $(".badge#new").css("background", $("#background").val())
            $(".badge#new span").css("color", $("#text").val())
            $(".badge#new span").text($("input[name=name]").val())
        }

        function rgbObjectToString(rgb){
            return '#' + intToHex(rgb.r) + intToHex(rgb.g) + intToHex(rgb.b);
        }

        function intToHex (int)
        {
            var hexstring = int.toString(16);
            if(hexstring.length===1)hexstring='0'+hexstring;
            return hexstring;
        }
    })
</script>
<style>
    .xe-shop-tag {
        display: inline-block;
        padding: 2px 4px;
        background-color: #ddd;
        font-size: 9px;
        color: #333;
        text-transform: uppercase;
        transform: translateY(-1px);
    }

    .cp-default {
        padding: 12px;
        width: 130px;
        height:100px;
        position:relative;
    }

    .cp-default .picker {
        width: 100px;
        height: 100px;
        position:absolute;
    }

    .cp-default .slide {
        width: 10px;
        height: 100px;
        position:absolute;
        left:120px;
    }
    .badge-container{
        height: 100px;
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        width: 100px;
    }
    .badge {
        background-color: #444;
        box-shadow: 0 0 3px 2px rgba(0,0,0,0.8);
        height: 100px;
        left: -50px;
        position: absolute;
        top: -50px;
        width: 100px;

        -webkit-transform: rotate(-45deg);
    }

    .badge span {
        color: #f5f5f5;
        font-family: sans-serif;
        font-size: 1.005em;
        left: 12px;
        top: 78px;
        position: absolute;
        width: 80px;
    }
</style>
