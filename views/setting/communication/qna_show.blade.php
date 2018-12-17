<div class="panel">
    <div class="panel-heading">
        <div class="panel-title">
            문의
        </div>
    </div>
    <div class="panel-body">
        @include(\Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::view('product.object.qna'),['product'=>$item->target, 'list'=>[$item] ,'total'=>false])
    </div>
</div>
