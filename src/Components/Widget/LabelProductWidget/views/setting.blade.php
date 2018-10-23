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
