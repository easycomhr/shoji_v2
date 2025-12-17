<div class="input-group area-date-dominus"  data-td-target-input="nearest" data-td-target-toggle="nearest">
    <input id="{{ $name_html }}_input" type="text" class="form-control input-date-dominus" data-td-target="#{{ $name_html }}" name="{{ $name_html }}" value="{{ $value ?? '' }}" />
    <span class="input-group-text" data-td-target="#{{ $name_html }}" data-td-toggle="datetimepicker">
        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
    </span>
</div>

<div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
    @error($name_html)
    {{ $message }}
    @enderror
</div>