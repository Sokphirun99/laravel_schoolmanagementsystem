@php
$selected_value = (isset($dataTypeContent->{$row->field}) && !is_null(old($row->field, $dataTypeContent->{$row->field}))) ? old($row->field, $dataTypeContent->{$row->field}) : old($row->field);
@endphp
<select class="form-control select2" name="{{ $row->field }}" data-name="{{ $row->display_name }}">
    <option value="">{{ __('voyager::generic.select_one') }}</option>
    @foreach($roles as $key => $role)
        <option value="{{ $key }}" @if($selected_value == $key) selected="selected" @endif>{{ $role }}</option>
    @endforeach
</select>
