{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/colorpicker.min.js')))->appendTo('body')->load() }}
@section('page_title')
<h2>라벨 수정</h2>
@endsection

<form method="post" action="{{ route('xero_commerce::setting.label.update', ['id'=>$label->id]) }}">
    {!! csrf_field() !!}
    <div class="panel">
        <div class="panel-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>라벨 이름</label>
                            <input class="form-control" name="name" value="{{ $label->name }}" />
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>영어 이름</label>
                            <input class="form-control" name="eng_name"
                            value="{{ $label->eng_name }}"/>
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
                            id="background" value="{{$label->background_color}}"/>
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
                            value="{{$label->text_color}}"/>
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
                </div>
            </div>
        </div>
        <div class="panel-footer text-right">
            <button class="btn btn-primary btn-lg" type="submit" class="xe-btn xe-btn-positive">수정</button>
        </div>
    </div>
</form>

<script>
    $(function () {
        var b_cp = ColorPicker(document.getElementById('background_cp'), function (hex, hsv, rgb) {
            $("#background").val(rgbObjectToString(rgb))
            $("#background_view").css('background',rgbObjectToString(rgb))
            changeLabel()
        })

        b_cp.setRgb({r: parseInt('{{substr($label->background_color,1,2) }}', 16), g: parseInt('{{substr($label->background_color,3,2)}}', 16), b: parseInt('{{substr($label->background_color,5,2)}}', 16)})

        var t_cp = ColorPicker(document.getElementById('text_cp'), function (hex, hsv, rgb) {
            $("#text").val(rgbObjectToString(rgb))
            $("#text_view").css('background',rgbObjectToString(rgb))
            changeLabel()
        })

        t_cp.setRgb({r: parseInt('{{substr($label->text_color,1,2) }}', 16), g: parseInt('{{substr($label->text_color,3,2) }}', 16), b: parseInt('{{substr($label->text_color,5,2) }}', 16)})

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

