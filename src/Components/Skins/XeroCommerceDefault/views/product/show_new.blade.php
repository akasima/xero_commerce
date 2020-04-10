@php
$skin = \Xpressengine\Plugins\XeroCommerce\Components\Skins\XeroCommerceDefault\XeroCommerceDefaultSkin::class;
/** @var \Xpressengine\Plugins\XeroCommerce\Models\Product $product */
@endphp
@if($product->state_deal == \Xpressengine\Plugins\XeroCommerce\Models\Product::DEAL_ON_SALE)
    <div class="hide" id="page">
        @foreach($product->getCategorys() as $key=>$item)
            @include($skin::view('product.object.category'),[
            'categorys'=>$category,
            'target'=>$item,
            'first'=>$key===0])
        @endforeach
        <main>
            <div class="inner-main">
                <div class="view-header">
                    @include($skin::view('product.object.image'),['images'=>$product->getImages()])
                    <div class="view-info-detail"><h2 class="title-view">
                            {{$product->getName()}}</h2>
                        <p class="text-summary">{{$product->sub_name}}</p>
                        <div class="box-label"></div>
                        <dl class="price">
                            <dt>판매가</dt>
                            <dd><span class="per">{{$product->discount_percentage}}%</span> <span
                                    class="discount">{{number_format($product->original_price)}}<i>원</i></span>
                            </dd>
                        </dl>
                        <dl class="sale">
                            <dt>할인가</dt>
                            <dd>{{number_format($product->sell_price)}}<i>원</i></dd>
                        </dl>
                        @include($skin::view('product.object.delivery'),['delivery'=>$product->getDelivery()])
                        @include($skin::view('product.object.option'),[
                            'optionType'=>$product->option_type,
                            'options'=>$product->getAvailableOptions(),
                            'optionItems'=>$product->getAvailableOptionItems(),
                            'customOptions'=>$product->getAvailableCustomOptions(),
                            'choose'=>[]
                        ])
                        <div class="box-button">
                            <a href="#" class="link-buy"onclick="event.preventDefault(); productPage.buyPage()">구매하기</a>
                            <button type="button" class="btn-cart reset-button" onclick="productPage.cartPage()">장바구니</button>
                            <button type="button" class="btn-like reset-button @if((app('xero_commerce.wishHandler')->isWish($product))) active @endif" onclick="productPage.toggleWish()"><i
                                    class="xi-heart"></i> 찜하기
                            </button>
                        </div>
                    </div>
                </div>
                <div class="area-view">
                    <ul class="tab-view reset-list">
                        <li class="item-view"><a
                                data-target=".detail-information"
                                href="#"
                                class="link-view active">상품정보</a></li>
                        <li class="item-view"><a
                                data-target=".detail-qna"
                                href="#"
                                class="link-view">Q&A</a></li>
                        <li class="item-view"><a
                                data-target=".detail-feedback"
                                href="#"
                                class="link-view">상품후기</a></li>
                        <li class="item-view"><a
                                data-target=".detail-as"
                                href="#"
                                class="link-view">배송/교환/환불</a></li>
                    </ul>
                    <div class="view-content">
                        <div class="detail-information">
                            @include($skin::view('product.object.information'),compact('product'))
                        </div>
                        <div class="detail-qna hide">
                            @include($skin::view('product.object.qna'),['product'=>$product, 'list'=>app('xero_commerce.qnaHandler')->get($product)])
                        </div>
                        <div class="detail-feedback hide">
                            @include($skin::view('product.object.feedback'),['list'=>app('xero_commerce.feedbackHandler')->get($product)])
                        </div>
                        <div class="detail-as hide">
                            @include($skin::view('product.object.as'),compact('product'))
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <form id="orderForm"></form>
    </div>
    <input type="hidden" id="csrf_token" value="{{csrf_token()}}">
@else
    {{$product->getDealStates()[$product->state_deal]}} 상품입니다.
@endif
<script>
    $(function(){
        $("a.link-view").click(function(event){
            event.preventDefault();
            $(".link-view").removeClass('active');
            $(this).addClass("active");
            var target = $(this).attr("data-target")
            $(".view-content > div").addClass("hide");
            $(target).removeClass("hide");
        });
        $('#page').removeClass('hide');
    });

    var productPage={
        choose: function () {
            return JSON.parse($("[name=choose]").val());
        },
        delivery: function () {
            return $("[name=delivery]").val();
        },
        count: function () {
            return Number(this.choose().map(function(v){
                return Number(v.count)
            }).reduce(function (a, b) {return a+b;}, 0))
        },
        addCart: function (success, fail) {
            var val = this.validate()
            if (!val.status) {
                alert(val.msg)
                return
            }
            $.ajax({
                url: "{{route('xero_commerce::product.cart', ['product'=> $product])}}",
                data: {
                    options: this.choose(),
                    delivery: this.delivery(),
                    _token: "{{csrf_token()}}"
                },
                method: 'post'
            }).done(function (res) {
                success(res)
            }).fail(function (err){
                fail(err)
            })
        },
        cartPage: function () {
            this.addCart(function(res) {
                var conf = confirm('장바구니에 담았습니다. 장바구니로 갈까요?')
                if (conf) {
                    document.location.href = "{{route('xero_commerce::cart.index')}}"
                }
            }, function(err) {
                console.log(err)
            })
        },
        buyPage: function () {
            this.addCart(function(res) {
                $.ajax({
                    url: "{{route('xero_commerce::order.register')}}",
                    data: {
                        cart_id: [res.id],
                        _token: "{{csrf_token()}}"
                    },
                    method: 'post'
                }).done(function(res) {
                    var form = document.getElementById("orderForm");
                    form.setAttribute('action', res.url)
                    form.setAttribute('method', 'get')
                    var order_id = document.createElement('input')
                    order_id.setAttribute('type', 'hidden')
                    order_id.setAttribute('name', 'order_id')
                    order_id.setAttribute('value', res.order_id)
                    form.appendChild(order_id)
                    form.submit()
                })
            }, function(err) {
                console.log(err)
            })
        },
        validate: function () {
            var validate =
                {
                    status: true,
                    msg: ''
                }

            if (this.choose().length === 0) {
                validate.status = false
                    validate.msg += '선택한 상품이 없습니다.'
            }

            if (this.count() < 1) {
                validate.status = false
                    validate.msg += '선택한 상품의 갯수가 없습니다.'
            }

            @if($product->min_buy_count)

            if ({{$product->min_buy_count}} > this.count()) {
                validate.status = false
                    validate.msg += '선택한 상품의 갯수가 최소 구매 수량보다 부족합니다.'
            }

            @endif

            @if($product->min_buy_count)

            if ({{$product->min_buy_count}} < this.count()) {
                validate.status = false
                    validate.msg += '선택한 상품의 갯수가 최대 구매 수량을 넘었습니다.'
            }

            @endif

            return validate
        },
        toggleWish: function () {
            if ({{\Illuminate\Support\Facades\Auth::check()?'true':'false'}}) {
                $.ajax({
                    url: "{{route('xero_commerce::product.wish.toggle',['product'=>$product])}}",
                    method: 'get'
                }).done(function(res) {
                    if (Number(res)) {
                        var conf = confirm('관심상품에 담았습니다. 관심상품으로 갈까요?')
                        if (conf) {
                            document.location.href = "{{route('xero_commerce::wish.index')}}"
                        }
                        $(".btn-like").addClass("active")
                    } else {
                        alert('관심상품에서 제거했습니다.')
                        $(".btn-like").removeClass("active")
                    }
                }).fail(function(err) {
                    console.log(err)
                })
            }else{
                XE.toast('warning','로그인 후 사용할 수 있습니다')
            }
        }
     }
</script>
<style>
    .btn-like.active > .xi-heart {
        color:red;
    }
</style>
