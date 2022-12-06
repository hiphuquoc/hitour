@if(Session::has('toast'))
    @php
        $toast    = Session::get('toast');
        Session::forget('toast');
    @endphp
    <div class="toastBox {{ $toast['type'] ?? null }}"><div class="toastBox_title">{{ $toast['title'] ?? null }}</div><div class="toastBox_content">{{ $toast['message'] ?? null }}</div></div>
@endif