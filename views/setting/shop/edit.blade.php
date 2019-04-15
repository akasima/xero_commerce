{{--@deprecated since ver 1.1.4--}}
<?php
use Xpressengine\Plugins\XeroCommerce\Models\Shop;
use Xpressengine\Plugins\XeroCommerce\Plugin;
?>

@section('page_title')
    <h2>입점몰 수정</h2>
@endsection
{{ XeFrontend::js(asset(Xpressengine\Plugins\XeroCommerce\Plugin::asset('assets/js/index.js')))->appendTo('body')->load() }}
<form method="post" action="{{ route('xero_commerce::setting.config.shop.update', ['shopId' => $shop->id]) }}"
    data-rule="shop" data-rule-alert-type="toast" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row">
        <div class="col-sm-12">
            <div class="panel-group">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6">
                                {{uio('formText', [
                                'label'=>'입점몰 이름',
                                'name'=>'shop_name',
                                'description'=>'입점몰 이름입니다',
                                'data-valid-name' => '입점몰 이름',
                                'value'=>$shop->shop_name
                                ])}}

                                {{uio('formText', [
                                'label'=>'입점몰 id',
                                'name'=>'shop_eng_name',
                                'description'=>'영문으로 된 입점몰 id입니다.',
                                'data-valid-name' => '입점몰 id',
                                'value'=>$shop->shop_eng_name
                                ])}}

                                @if ($shop->shop_type != Shop::TYPE_BASIC_SHOP)
                                <div class="form-group">
                                    <label>입점몰 형태</label>
                                    <select name="shop_type" class="form-control" value="{{$shop->shop_type}}">
                                        @foreach($shopTypes as $key=>$value)
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                            </div>
                            <div class="col-lg-6">

                                <div id="component-container">
                                    <user-search-component label="관리자ID" name="user_id" url="{{route('xero_commerce::setting.search.user',['keyword'=>''])}}" :value="{{json_encode($shop->users)}}"></user-search-component>
                                </div>
                                <div id="shownImage" class="show">
                                    <b style="color:blue; cursor:pointer" href="#" onclick="editImages.edit()">수정</b>
                                    <div class="form-group">
                                        <label>로고이미지</label> <br>
                                        <img src="{{$shop->logo}}" alt="">
                                    </div>
                                </div>
                                <div id="editImage" class="editImages hide">
                                    <b style="color:blue; cursor:pointer" href="#" onclick="editImages.reset()">초기화</b>
                                    {!! uio('formImage',
                                    [
                                        'label'=>'로고이미지',
                                        'name'=>'logo'
                                    ]) !!}
                                </div>
                                <script>
                                    var editImages={
                                        edit () {
                                            $("#shownImage").addClass('hide').removeClass('show');
                                            $("#editImage").addClass('show').removeClass('hide');
                                            $("#shownImage input").prop('disabled', true)
                                            $("#editImage input").prop('disabled', false)
                                        },
                                        reset () {
                                            $("#editImage").addClass('hide').removeClass('show');
                                            $("#shownImage").addClass('show').removeClass('hide');
                                            $("#shownImage input").prop('disabled', false)
                                            $("#editImage input").prop('disabled', true)
                                        }
                                    }
                                    $(function(){
                                        $(".editImages input").prop('disabled',true)
                                    })
                                </script>
                            </div>
                            <div class="col-lg-12">
                                <label for="xeContentEditorDeliveryInfo">배송정보</label>
                                {!! editor(Plugin::getId(), [
                                  'content' => $shop->delivery_info,
                                  'contentDomName' => 'delivery_info',
                                  'contentDomId' => 'xeContentEditorDeliveryInfo',
                                ]) !!}

                                <label for="xeContentEditorAsInfo">반품/교환 정보</label>
                                {!! editor(Plugin::getId(), [
                                  'content' => $shop->as_info,
                                  'contentDomName' => 'as_info',
                                  'contentDomId' => 'xeContentEditorAsInfo',
                                ]) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="xe-btn xe-btn-success">수정</button>
</form>

<script>
    $('textarea[name=delivery_info]').attr('data-valid-name', '배송정보');
    $('textarea[name=as_info]').attr('data-valid-name', '반품/교환 정보');
</script>
