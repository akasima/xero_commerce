<div class="form-group">
    <label>라벨</label>
    <select name="label_id" class="form-control">
        @if (isset($args['label_id']) === true)
            @foreach ($labels as $label)
                <option value="{{ $label['id'] }}" @if ($args['label_id'] == $label['id']) selected @endif>{{ xe_trans($label['name']) }}</option>
            @endforeach
        @else
            @foreach ($labels as $label)
                <option value="{{ $label['id'] }}">{{ xe_trans($label['name']) }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="form-group">
    <label>출력할 카테고리 ID 설정</label>
    <input class="form-control" type="text" name="category_item_id" value="{{ array_get($args, 'category_item_id') }}">
</div>

<div class="form-group">
    <label>출력할 상품 ID 설정</label>
    <input class="form-control" type="text" name="product_id" value="{{ array_get($args, 'product_id') }}">
</div>