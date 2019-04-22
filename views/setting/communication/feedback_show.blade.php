{{--@deprecated since ver 1.1.4--}}
<div class="panel panel-info">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12" style="text-align: right">
                {{$item->user->getDisplayName()}} /
                {{$item->created_at}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {!! uio(\Xpressengine\Plugins\XeroCommerce\Components\UIObjects\Star\StarUIObject::getId(), [
                'id'=>'feedback'.$item->id,
                'size'=>'20',
                'star'=>$item->score,
                'mode'=>'read']) !!}
            </div>
            <div class="col-sm-12">
                {{$item->content}}
            </div>
        </div>
    </div>
</div>
