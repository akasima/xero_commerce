@section('page_title')
    <h2>라벨 관리</h2>
@endsection

{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/colorpicker.min.js')))->appendTo('body')->load() }}
<div class="row" id="component-container">
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
                                <th>배경색</th>
                                <th>글자색</th>
                                <th>미리보기</th>
                                <th>관리</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($labels as $label)
                                <tr>
                                    <td>{{ $label->name }}</td>
                                    <td>{{ $label->eng_name }}</td>
                                    <td>
                                        @if($label->background_color && $label->text_color)
                                            <div
                                                style="width:20px;height:20px;background-color:{{$label->background_color}}; border:2px black solid"></div>@endif
                                    </td>
                                    <td>
                                        @if($label->background_color && $label->text_color)
                                            <div
                                                style="width:20px;height:20px;background-color:{{$label->text_color}}; border:2px black solid"></div>@endif
                                    </td>
                                    <td><span class="xe-shop-tag"
                                              @if($label->background_color && $label->text_color)style="background: {{$label->background_color}}; color:{{$label->text_color}}" @endif>{{$label->name}}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('xero_commerce::setting.label.edit', ['id' => $label->id]) }}"
                                           class="xe-btn">수정</a>
                                        <form method="post"
                                              action="{{ route('xero_commerce::setting.label.remove', ['id' => $label->id]) }}">
                                            {!! csrf_field() !!}
                                            <button type="submit" class="xe-btn xe-btn-danger">삭제</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <form method="post" action="{{ route('xero_commerce::setting.label.store') }}">
                            {!! csrf_field() !!}
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>라벨 이름</label>
                                        <input class="form-control" name="name" value="{{ (Request::old('name'))?:'라벨명' }}" />
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>영어 이름</label>
                                        <input class="form-control" name="eng_name"
                                               value="{{ Request::old('eng_name') }}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>배경색</label>
                                        <div style="position:relative">
                                            <div id="background_cp" class="cp-default"></div>
                                            <div id="background_view" style="width:124px;height:124px;position:absolute;top:0px;left:150px;padding:12px;"></div>
                                        </div>
                                        <input class="form-control" name="background_color" type="hidden"
                                               id="background" value="#dddddd"/>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>글자색</label>
                                        <div style="position:relative">
                                            <div id="text_cp" class="cp-default"></div>
                                            <div id="text_view" style="width:124px;height:124px;position:absolute;top:0px;left:150px;padding:12px;"></div>
                                        </div>
                                        <input class="form-control" name="text_color" type="hidden" id="text"
                                               value="#333333"/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label>미리보기</label>
                                        <span class="xe-shop-tag" id="new"></span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <button class="btn btn-default btn-block" type="submit"
                                            class="xe-btn xe-btn-positive">추가
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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

        b_cp.setRgb({r: parseInt('dd', 16), g: parseInt('dd', 16), b: parseInt('dd', 16)})

        var t_cp = ColorPicker(document.getElementById('text_cp'), function (hex, hsv, rgb) {
            $("#text").val(rgbObjectToString(rgb))
            $("#text_view").css('background',rgbObjectToString(rgb))
            changeLabel()
        })

        t_cp.setRgb({r: parseInt('33', 16), g: parseInt('33', 16), b: parseInt('33', 16)})

        $("input").on("input",function(){
            changeLabel()
        })

        function changeLabel(){
            $("span#new").css("background", $("#background").val())
            $("span#new").css("color", $("#text").val())
            $("span#new").text($("input[name=name]").val())
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
</style>
