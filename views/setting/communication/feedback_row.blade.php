{{--@deprecated since ver 1.1.4--}}
@foreach($list as $item)
    <tr>
        <td>{{$item->target->getName()}}</td>
        <td>{!! uio(\Xpressengine\Plugins\XeroCommerce\Components\UIObjects\Star\StarUIObject::getId(), [
        'id'=>$item->id,
        'star'=>$item->score/2,
        'size'=>20,
        'mode'=>'read']) !!} <a href="{{route('xero_commerce::setting.communication.show',['type'=>'feedback','id'=>$item->id])}}">{{$item->content}} {{$item->score}}</a></td>
        <td style="text-align: center">{{$item->user->getDisplayName()}}</td>
        <td style="text-align: center">
            <span class="real_date date hide">{{$item->created_at}}</span>
            <span class="format_date date">{{$item->created_at->diffForHumans()}}</span>
        </td>
    </tr>
@endforeach
