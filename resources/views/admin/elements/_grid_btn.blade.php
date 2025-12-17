<a class="btn-act-grid {{ $act_cls ?? '' }} {{ $cls }}" href="{{ $url ?? 'javascript:;' }}"
   @if(!empty($id)) data-id="{{ $id }}" @endif
   @if(!empty($name)) data-name="{{ $name }}" @endif
>
    {{ $label ?? '削除' }}
</a>
