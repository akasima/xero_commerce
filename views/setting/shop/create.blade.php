@section('page_title')
    <h2>입점몰 추가</h2>
@endsection
<?php
use Xpressengine\Plugins\XeroCommerce\Plugin;
?>
<form method="post" action="{{ route('xero_commerce::setting.config.shop.store') }}">
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
                                'value'=>Request::old('shop_name')
                                ])}}

                                {{uio('formText', [
                                'label'=>'입점몰 영어 이름',
                                'name'=>'shop_eng_name',
                                'description'=>'입점몰 영어표기명입니다',
                                'value'=>Request::old('shop_eng_name')
                                ])}}


                                {{uio('formSelect', [
                                'label'=>'입점몰 형태',
                                'name'=>'shop_type',
                                'description'=>'입점몰 사업 형태를 결정합니다',
                                'options'=>$shopTypes
                                ])}}

                            </div>
                            <div class="col-lg-6">


                                {{uio('formSelect', [
                                'label'=>'배송회사',
                                'name'=>'delivery_company',
                                'description'=>'배송을 담당할 회사를 선택해주세요',
                                'options'=>$deliveryCompanyOptions,
                                ])}}
                                {{uio('formText', [
                                'label'=>'배송비',
                                'name'=>'delivery_fare',
                                'description'=>'배송시 청구할 배송비입니다.',
                                'value'=>Request::old('delivery_fare')
                                ])}}

                                {{uio('formText', [
                                'label'=>'관리자ID',
                                'name'=>'user_id',
                                'description'=>'입점몰을 관리할 관리자계정ID입니다',
                                'value'=>Request::old('user_id')
                                ])}}
                            </div>
                            <div class="col-lg-12">
                                <label for="xeContentEditorDeliveryInfo">배송정보</label>
                                {!! editor(Plugin::getId(), [
                                  'content' => Request::old('delivery_info'),
                                  'contentDomName' => 'delivery_info',
                                  'contentDomId' => 'xeContentEditorDeliveryInfo',
                                ]) !!}

                                <label for="xeContentEditorAsInfo">반품/교환 정보</label>
                                {!! editor(Plugin::getId(), [
                                  'content' => Request::old('as_info'),
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

    <button type="submit" class="xe-btn xe-btn-success">등록</button>
</form>
