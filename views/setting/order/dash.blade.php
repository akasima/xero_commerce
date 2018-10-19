<div class="xe-row">
    <div class="xe-col-lg-8">
        주문현황 (그래프)
        <canvas id="orderStat" width="600" height="200">No Canvas</canvas>
    </div>
    <div class="xe-col-lg-4">
        처리중 주문
        <table class="xe-table xe-table-striped">
            <thead>
                <tr>
                    <th>상태변경</th>
                    <th>건수</th>
                    <th>금액</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>입금대기</td>
                    <td>{{$dash['결제대기']}}</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>상품준비</td>
                    <td>{{$dash['상품준비']}}</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>배송중</td>
                    <td>{{$dash['배송중']}}</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>취소처리</td>
                    <td>{{$dash['취소중']}}</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>환불처리</td>
                    <td>{{$dash['환불중']}}</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>교환처리</td>
                    <td>{{$dash['교환중']}}</td>
                    <td>0</td>
                </tr>
            </tbody>
            </tbody>
            </tbody>
        </table>
    </div>
</div>
<div class="xe-row">
    <div class="xe-col-lg-12">
        결제수단별 주문현황
    </div>
</div>
<div class="xe-row">
    <div class="xe-col-lg-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <table class="xe-table xe-table-bordered" id="orderList">
                    <thead>
                    <tr>
                        <th rowspan="3"><input type="checkbox"></th>
                        <th colspan="2" rowspan="2">주문번호</th>
                        <th>주문자</th>
                        <th>주문자전화</th>
                        <th>받는분</th>
                        <th rowspan="3">주문합계 <br> 선불배송비포함</th>
                        <th rowspan="3">입금합계</th>
                        <th rowspan="3">주문취소</th>
                    </tr>
                    <tr>
                        <th>회원ID</th>
                        <th>주문상품수</th>
                        <th>누적주문수</th>
                    </tr>
                    <tr>
                        <th>주문상태</th>
                        <th>결제수단</th>
                        <th>운송장번호</th>
                        <th>배송회사</th>
                        <th>배송일시</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($list as $item)
                        <tr>
                            <td rowspan="3"><input type="checkbox"></td>
                            <td colspan="2" rowspan="2">{{$item->order_no}}</td>
                            <td>{{$item->userInfo->name}}</td>
                            <td>{{$item->userInfo->phone}}</td>
                            <td>{{$item->orderItems[0]->delivery->recv_name}}</td>
                            <td rowspan="3">{{$item->orderItems->sum(function($item){return $item->getSellPrice()+$item->getFare();})}}</td>
                            <td rowspan="3">{{$item->payment->price}}</td>
                        </tr>
                        <tr>
                            <td>{{\Xpressengine\User\Models\User::find($item->user_id)->email}}</td>
                            <td>{{$item->orderItems->sum(function($item){return $item->getCount();})}}</td>
                            <td>{{$item->where('user_id',$item->user_id)->count()}}</td>
                        </tr>
                        <tr>
                            <td>{{$item->getStatus()}}</td>
                            <td>{{$item->payment->getMethod()}}</td>
                            <td>{{$item->orderItems[0]->delivery->ship_no}}</td>
                            <td>{{$item->orderItems[0]->delivery->company->name}}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{--<div class="xe-col-lg-4">--}}
        {{--1:1문의--}}
    {{--</div>--}}
    {{--<div class="xe-col-lg-4">--}}
        {{--상품문의--}}
    {{--</div>--}}
    {{--<div class="xe-col-lg-4">--}}
        {{--사용후기--}}
    {{--</div>--}}
</div>
<style>
    #orderList thead tr th {
        text-align: center;
        padding:8px 5px;
        background: #ddd
    }
</style>
<script>
    $(function(){
      var format = function(date){
        return (date.getMonth()+1) + '/' + date.getDate();
      }
      var ctx=document.getElementById('orderStat');
      var days = {!! json_encode($week['days']) !!};
      var count = {!! json_encode($week['count']) !!};
      var chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: days,
          datasets: [
            {
              label: '주문수',
              data: count
            }
          ]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true,
                stepSize: 1,
                maxTicksLimit: 10
              }
            }]
          }
        }
      })
    })
</script>
