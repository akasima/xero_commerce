<h2>주문 및 결제</h2>
<form action="#">
    {{csrf_field()}}

    <table class="xe-table">
        <thead>
        <tr>
            <th></th>
            <th>상품정보</th>
            <th>상품금액</th>
            <th>할인금액</th>
            <th>수량</th>
            <th>배송비</th>
            <th>주문금액</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->orderItems as $items)
            <tr>
                <td><img src="{{$items->getThumbnailSrc()}}" width="150px" height="150px" alt=""></td>
                <td>
                    @foreach($items->renderGoodsInfo() as $key => $row)
                        <span @if($key==1) style="color:grey" @endif>{{$row}}</span> <br>
                    @endforeach
                </td>
                <td>
                    {{number_format($items->getOriginalPrice())}} 원
                </td>
                <td>
                    {{number_format($items->getDiscountPrice())}} 원
                </td>
                <td>
                    {{$items->getCount()}} 개
                </td>
                <td>
                    선불
                </td>
                <td>
                    <b>{{number_format($items->getSellPrice())}} 원</b>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="xe-row">
        <div class="xe-col-lg-8">
            <div>
                <h4>주문고객 정보</h4>
                <table class="xe-table">
                    <tr>
                        <th>이름</th>
                        <td>{{\Illuminate\Support\Facades\Auth::user()->display_name}}</td>
                    </tr>
                    <tr>
                        <th>연락처</th>
                        <td>010-0000-0000</td>
                    </tr>
                    <tr>
                        <th>이메일</th>
                        <td>{{\Illuminate\Support\Facades\Auth::user()->email}}</td>
                    </tr>
                </table>
            </div>
            <div>
                <h4>배송지 정보</h4>
                <table class="xe-table">
                    <tr>
                        <th>배송지 선택</th>
                        <td><input type="checkbox" name="delivery_option" value="default" checked="checked"> 기본 배송지 <input type="checkbox" name="delivery_option" value="new"> 신규 배송지</td>
                    </tr>
                    <tr>
                        <th>이름</th>
                        <td><input type="text" value="{{\Illuminate\Support\Facades\Auth::user()->display_name}}"></td>
                    </tr>
                    <tr>
                        <th>연락처</th>
                        <td>010-<input type="text">-<input type="text"></td>
                    </tr>
                    <tr>
                        <th>주소</th>
                        <td>
                            <input type="text">
                            <button>우편번호</button>
                            <input type="text">
                            <input type="text">
                        </td>
                    </tr>
                    <tr>
                        <th>배송 메세지</th>
                        <td><input type="text"></td>
                    </tr>
                </table>
            </div>
            <div>
                <h4>할인 정보</h4>
                <table>
                    <tr>
                        <th>할인 쿠폰</th>
                        <td><input type="text">원</td>
                    </tr>
                    <tr>
                        <th>적립금 사</th>
                        <td><input type="text">원</td>
                    </tr>
                </table>
            </div>
            <div>
                <h4>결제 정보 입력</h4>
                <table>
                    <tr>
                        <th>간편 결제</th>
                        <td></td>
                        <th>일반 결제</th>
                        <td></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="xe-col-lg-4">
            <div class="card">
                <div class="card-title">
                    최종 결제금액
                </div>
                <div class="card-content">
                    <table class="xe-table">
                        <tr>
                            <th>상품금액</th>
                            <td>{{number_format($summary['original_price'])}} 원</td>
                        </tr>
                        <tr>
                            <th>할인금액</th>
                            <td>{{number_format($summary['discount_price'])}} 원</td>
                        </tr>
                        <tr>
                            <th>적립금 사용</th>
                            <td>0원</td>
                        </tr>
                        <tr>
                            <th>배송비</th>
                            <td>{{number_format($summary['fare'])}} 원</td>
                        </tr>
                        <tr>
                            <th>최종 결제금액</th>
                            <td>{{number_format($summary['sum'])}} 원</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <a href="{{instance_route('xero_commerce::order.fail', ['order'=>$order->id])}}"><button type="button" class="xe-btn xe-btn-lg xe-btn-black xe-btn-block">결제하기</button></a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                    <input type="checkbox">구매동의
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse in">
                            <div class="panel-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                    <input type="checkbox">개인정보 수집 및 이용동의
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="panel-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4 class="panel-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                    <input type="checkbox">개인정보 제3자 제공/위탁 동
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse">
                            <div class="panel-body">
                                Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>