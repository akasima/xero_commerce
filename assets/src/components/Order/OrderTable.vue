<template>
    <table class="xe-table">
        <thead>
        <tr>
            <th width="170">주문일자</th>
            <th>상품정보</th>
            <th>결제금액</th>
            <th>수량</th>
            <th>배송비</th>
            <th>주문상태</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="list.length===0">
            <td style="text-align: center" colspan="6">
                <i class="xi-error"></i> 조회된 주문이 없습니다.
            </td>
        </tr>
        <template v-for="item in list" v-if="list.length>0">
            <tr v-for="(orderitem, key) in item.orderItems">
                <td style="text-align: center; background:#f1f1f1;cursor:pointer" :rowspan="item.orderItems.length"
                    v-if="key===0" @click="url(detailUrl+'/'+item.id)">
                    <p>{{item.order_no}}</p>
                    <p style="color:#aaa">{{item.created_at.substr(0,16)}}</p>
                    <h3>{{item.status}}</h3>
                </td>
                <td>
                    <div class="row">
                        <div class="col">
                            <img :src="orderitem.src" alt="" width="120" height="120">
                        </div>
                        <div class="col">
                            <span v-for="html in orderitem.info" v-html="html"></span>
                        </div>
                    </div>
                </td>
                <td>
                    {{orderitem.sell_price.toLocaleString()}}
                </td>
                <td>
                    {{orderitem.count}} 개
                </td>
                <td>
                    {{orderitem.delivery_pay}} <br>
                    {{orderitem.fare.toLocaleString()}}
                </td>
                <td>
                    <p>{{orderitem.status}}</p>
                    <p>
                        <button class="xe-btn xe-btn-default" @click="url(orderitem.delivery_url)">배송조회</button>
                    </p>
                    <p>
                        <span v-if="inDelivery(item)">
                        <a style="cursor:pointer" @click="url(asUrl+'/change/'+item.id+'/'+orderitem.id)">교환</a> /
                        <a style="cursor:pointer" @click="url(asUrl+'/refund/'+item.id+'/'+orderitem.id)">환불</a>
                        </span>
                        <a v-if="notInDelivery(item)" style="cursor:pointer" @click="url(cancelUrl+'/'+item.id)">취소</a>
                    </p>
                </td>
            </tr>
        </template>
        </tbody>
        <tfoot v-if="typeof paginate!=='undefined'">
        <tr>
            <td colspan="6">
                <div style="width:100%; position:relative">
                    <div style="position:absolute; left:0; bottom:0">
                        total : {{currentCount}} / {{paginate.total}} ({{paginate.from}} ~ {{paginate.to}})
                    </div>
                    <div style="margin:0 auto; width:50%; text-align: center">
                        <a @click="$emit('page',1)">처음</a>
                        <a v-if="paginate.current_page-2>0" @click="$emit('page',paginate.current_page-2)">{{paginate.current_page-2}}</a>
                        <a v-if="paginate.current_page-1>0" @click="$emit('page',paginate.current_page-1)">{{paginate.current_page-1}}</a>
                        <b><a @click="$emit('page',first_page)">{{paginate.current_page}}</a></b>
                        <a v-if="paginate.current_page+1<=paginate.last_page"
                           @click="$emit('page',paginate.current_page+1)">{{paginate.current_page+1}}</a>
                        <a v-if="paginate.current_page+2<=paginate.last_page"
                           @click="$emit('page',paginate.current_page+2)">{{paginate.current_page+2}}</a>
                        <a @click="$emit('page',paginate.last_page)">끝</a>
                    </div>
                    <div style="position:absolute; right:0; bottom:0">

                    </div>
                </div>
            </td>
        </tr>
        </tfoot>
    </table>
</template>

<script>
    export default {
        name: "OrderTable",
        props: [
            'list', 'paginate', 'asUrl', 'detailUrl', 'cancelUrl'
        ],
        computed: {
            currentCount() {
                if (this.paginate.total <= this.paginate.per_page) {
                    return this.paginate.total
                } else if (this.paginate.current_page === this.paginate.last_page) {
                    return this.paginate.total % this.paginate.per_page
                } else {
                    return this.paginate.per_page
                }
            }
        },
        methods: {
            url(url) {
                window.open(url, 'target=_blank' + new Date().getTime())
            },
            inDelivery (item) {
                return item.status ==='배송중' || item.status==='배송완료'
            },
            notInDelivery( item ){
                return item.status ==='상품준비' || item.status==='결제대기'
            }
        }
    }
</script>

<style scoped>
    tfoot a {
        color: #0f74a8;
        cursor: pointer;
    }

</style>
