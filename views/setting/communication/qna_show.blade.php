<div class="panel">
    <div class="panel-heading">
        <div class="panel-title">
            문의
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12" style="text-align: right">
                {{$item->user->getDisplayName()}} /
                {{$item->created_at}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                {{$item->content}}
            </div>
        </div>
    </div>
    <div class="panel-body">

        @foreach($item->child as $child_item)
            <div class="panel" style="text-align: left">
                <div class="panel-body">
                    <p style="text-align: right">
                        <small>
                            {{$child_item->user->getDisplayName()}} /
                            {{$child_item->created_at}}
                        </small>
                    </p>
                    {{$child_item->content}}
                </div>
            </div>
        @endforeach
    </div>
</div>
