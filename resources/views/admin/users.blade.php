<!-- resources/views/admin/users.blade.php -->

@foreach($users as $user)
    <p>{{ $user->name }} 
        @if(in_array(Auth::id(), [4, 5, 9]))
            <a href="{{ route('impersonate.take', $user->id) }}">Impersonate</a>
        @endif
    </p>
@endforeach

@if(session()->has('impersonate'))
    <a href="{{ route('impersonate.leave') }}">Leave Impersonation</a>
@endif
