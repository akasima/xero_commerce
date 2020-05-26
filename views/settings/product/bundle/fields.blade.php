<div id="bundleItemManager" class="table-responsive" style="overflow-y: visible">
    <table class="table detail_info">
        <thead>
        <tr>
            <th>상품 코드</th>
            <th>상품명</th>
            <th>상품 옵션</th>
            <th>추가 옵션</th>
            <th>단가</th>
            <th>수량</th>
            <th>합계</th>
            <th>작업</th>
        </tr>
        </thead>
        <tbody>
            <tr v-for="(item, i) of items">
                <td>
                    <input type="hidden" v-bind:name="'bundle_items['+i+'][product_id]'" v-bind:value="item.product_id" />
                    @{{ item.product.product_code }}
                </td>
                <td>@{{ item.product.name }}</td>
                <td>
                    <select v-bind:name="'bundle_items['+i+'][product_variant_id]'">
                        <option v-for="variant of item.product.variants" v-bind:value="variant.id">@{{ variant.name }} (+@{{ variant.additional_price }}원)</option>
                    </select>
                </td>
                <td>
                    <div v-for="option of item.custom_options">
                        <input type="hidden" v-bind:name="'bundle_items['+i+'][custom_options]['+option.id+'][type]'" v-bind:value="option.type" />
                        <input type="hidden" v-bind:name="'bundle_items['+i+'][custom_options]['+option.id+'][name]'" v-bind:value="option.name" />
                        <span>@{{ option.name }} : </span>
                        <span v-html="renderInputHtml(i, option.input_html)"></span>
                    </div>
                </td>
                <td>@{{ item.product.sell_price }}</td>
                <td>
                    <div class="xe-spin-box">
                        <input type="hidden" v-bind:name="'bundle_items['+i+'][count]'" v-bind:value="item.count" />
                        <button type="button" @click="(item.count>1) ? item.count-- : ''"><i class="xi-minus-thin"></i><span class="xe-sr-only">감소</span></button>
                        <p>@{{item.count}}</p>
                        <button type="button" @click="item.count++"><i class="xi-plus-thin"></i><span class="xe-sr-only">증가</span></button>
                    </div>
                <td>@{{ item.product.sell_price * item.count }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger" @click="deleteBundleItem(i)">삭제</button>
                </td>
            </tr>
        <tr>
            <td colspan="8">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="상품 검색" v-model="searchKeyword" v-on:keydown.enter.prevent="search(searchKeyword)" >
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" @click="search(searchKeyword)">검색</button>
                    </span>
                </div><!-- /input-group -->
                <ul class="list-group" style="cursor: pointer;">
                    <li v-for="product of searchResults" class="list-group-item product-search-result" @click="addBundleItem(product)">@{{product.name}}</li>
                </ul>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<script>
    $(function() {
        new Vue({
            el: '#bundleItemManager',
            data: {
                searchKeyword: '',
                searchResults: [],
                items: {!! isset($product) ? $product->items->load('product.variants', 'product.customOptions')->toJson() : '[]' !!}
            },
            methods: {
                search(keyword) {
                    XE.Request.get('{{ route('xero_commerce:setting.product.search') }}', { 'product_name' : keyword })
                    .then(res => {
                        this.searchResults = res.data.products;
                    });
                },
                addBundleItem(product) {
                    this.items.push({
                        product: product,
                        product_id: product.id,
                        custom_options: product.custom_options,
                        count: 1
                    });
                    this.searchResults = [];
                    this.searchKeyword = '';
                },
                renderInputHtml(itemIndex, html) {
                    return html.replace('name=\"custom_options', 'name=\"bundle_items['+itemIndex+'][custom_options]');
                },
                deleteBundleItem(index) {
                    this.items.splice(index, 1);
                }
            },
            mounted() {

            }
        });
    })
</script>

<style>
.xe-spin-box {
    display: inline-block;
    border: 1px solid #c7c7ca;
    background-color: #fff;
    color: #898c93;
    text-align: center;
    width: 82px;
}

.xe-spin-box button {
    appearance: button;
    -moz-appearance: button;
    -webkit-appearance: button;
    border: 0;
    background-color: transparent;
    color: #666;
    cursor: pointer;
    display: inline-block;
    height: 100%;
    padding: 1px 3px;
}

.xe-spin-box button i {
    transform: translateY(2px);
}

.xe-spin-box button:first-child {
    border-right: 1px solid #c7c7ca;
}

.xe-spin-box button:last-child {
    border-left: 1px solid #c7c7ca;
}

.xe-spin-box p {
    display: inline-block;
    width: 30px;
    margin: 0;
    color: #666;
}

.xe-spin-box+p {
    display: inline-block;
}
</style>
