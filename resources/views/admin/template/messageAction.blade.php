@if(Session::has('message'))
    @php
        $message    = Session::get('message');
        Session::forget('message');
    @endphp
    <div class="js_message alert alert-{{ $message['type'] }}" style="display:inline-block;">
        <div class="alert-body">{!! $message['message'] !!}</div>
    </div>
@endif