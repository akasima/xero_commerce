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
        <table class="xe-table xe-table-striped" id="orderList">
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
                    <th rowspan="3">쿠폰</th>
                    <th rowspan="3">미수금</th>
                    <th rowspan="3">보기</th>
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
        </table>
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
        padding:8px 5px
    }
</style>
<script>
    $(function(){
      var format = function(date){
        return (date.getMonth()+1) + '/' + date.getDate();
      }
      var ctx=document.getElementById('orderStat');
      var day = new Date();
      var days = [];
      var count = [];
      for(var i = 0; i<7; i++) {
        days.push(format(day));
        day.setDate(day.getDate()-1);
        count.push(Math.floor(Math.random()*10));
      }
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