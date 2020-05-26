<div class="table-responsive" style="overflow-y: visible">
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
        </tr>
        </thead>
        <tbody>
            @foreach ($product->items as $item)
                <tr>
                    <td>{{ $item->product->product_code }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->productVariant->name }}</td>
                    <td>
                        @foreach($item->custom_options as $option)
                            {{ $option['name'] }} : {{ $option['display_value'] }}
                        @endforeach
                    </td>
                    <td>{{ $item->getSellPrice() }}</td>
                    <td>{{ $item->count }}</td>
                    <td>{{ $item->product->sell_price * $item->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
