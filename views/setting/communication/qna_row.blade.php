{{--@deprecated since ver 1.1.4--}}
@foreach($list as $item)
    <tr>
        <td>{{$item->target->getName()}}</td>
        <td><a href="{{route('xero_commerce::setting.communication.show',['type'=>'qna','id'=>$item->id])}}">@if($item->privacy) <i class="xi-lock"></i> @endif {{$item->content}} ({{$item->child()->count()}})</a> </td>
        <td style="text-align: center">{{$item->user->getDisplayName()}}</td>
        <td style="text-align: center">
            <span class="real_date date hide">{{$item->created_at}}</span>
            <span class="format_date date">{{$item->created_at->diffForHumans()}}</span>
        </td>
    </tr>
@endforeach
