@if(!empty(session('status')))
    @if(session('status') == 1)
        <div class="alert alert-success">{{ session('msg') }}</div>
    @elseif(session('status') == 2)
        <div class="alert alert-danger">{{ session('msg') }}</div>
    @endif
@endif
