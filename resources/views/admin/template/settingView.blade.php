<div class="flexBox" style="margin:0;display:inline-block;">
    <div class="input-group input-group-merge">
        <select class="form-select" style="max-width:60px;" tabindex="-1" aria-hidden="true" onChange="settingView('{{ $name ?? null }}', this.value);">
            @foreach($listItem as $item)
                @php
                    $selected = null;
                    if(!empty($default)&&$default==$item) $selected = 'selected';
                @endphp
                <option value="{{ $item }}" {{ $selected }}>{{ $item }}</option>
            @endforeach
        </select>
        <span class="input-group-text" style="background:#dee2e6;padding:0.5rem 0.75rem !important;">/ tổng <span style="font-weight:700;margin-left:0.5rem;">{{ $total ?? '-' }}</span></span>
    </div>
</div>