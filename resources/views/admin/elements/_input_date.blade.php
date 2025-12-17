<div class="input-group " id="{{ $name_html }}" data-td-target-input="nearest" data-td-target-toggle="nearest">
    <input type="text" value="{{ $value }}" name="{{ $name_html }}" class="form-control js-input-date" data-td-target="#{{ $name_html }}"/>
    <span class="input-group-text pointer" data-td-target="#{{ $name_html }}" data-td-toggle="datetimepicker">
            <i class="fas fa-calendar-alt fs-2"></i>
        </span>
</div>
<script>
    new tempusDominus.TempusDominus(document.getElementById("{{ $name_html }}"), {
        localization: {
            locale: "de",
            startOfTheWeek: 1,
            format: "dd/MM/yyyy"
        }
    });
</script>
