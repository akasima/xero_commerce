@section('page_title')
    <h3>상품평</h3>
@endsection
<div class="col-sm-12 text-right">
    <button class="btn btn-primary" onclick="location.href='{{url()->previous()}}'"><i class="xi-back"></i>돌아가기</button> <br>
</div>
<div class="col-sm-12">
    @include('xero_commerce::views.setting.communication.'.$type.'_show', ['item'=>$item])
</div>
<div class="col-sm-12">
    <div class="panel">
        <div class="panel-heading">
            <div class="panel-title">
                상품정보
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="box-image">
                        <span class="thumbnail" style="background-image:url({{$item->target->getThumbnailSrc()}})"></span>
                    </div>
                </div>
                <div class="col-sm-8">
                    <table class="table">
                        <tr>
                            <th>상품명</th>
                            <td>{{$target->name}}</td>
                        </tr>
                        <tr>
                            <th>가격</th>
                            <td>{{$target->sell_price }}</td>
                        </tr>
                        <tr>
                            <th>재고정보</th>
                            <td>{{$target->stock}}</td>
                        </tr>
                        <tr>
                            <th>평점</th>
                            <td>{{round($target->score,1)}} / 10</td>
                        </tr>
                        <tr>
                            <th>최근 상품평</th>
                            <td>
                                <table class="table">
                                    @foreach($target->feedbacks as $feedback)
                                        <tr>
                                            <td style="width:100px;">
                                                {{$feedback->user->getDisplayName()}}
                                            </td>
                                            <td style="width:100px;">
                                                {!! uio(\Xpressengine\Plugins\XeroCommerce\Components\UIObjects\Star\StarUIObject::getId(), [
                                                'id'=>'feedback_recent_'.$feedback->id,
                                                'star'=>$feedback->score/2,
                                                'size'=>10,
                                                'mode'=>'read'
                                                ]) !!}
                                            </td>
                                            <td>
                                                {{$feedback->content}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <th>최근 상품문의</th>
                            <td>
                                <table class="table">
                                    @foreach($target->qnas as $qna)
                                        <tr>
                                            <td style="width:100px;">
                                                {{$qna->user->getDisplayName()}}
                                            </td>
                                            <td>
                                                {{$qna->content}}
                                                ({{$qna->child()->count()}})
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .box-image{
        width: 100%;
    }
    .thumbnail{
        -webkit-background-size: cover;
        background-size: cover;
        background-position: 50% 50%;
        padding-top: 100%;
        display:block;
    }
</style>
