@extends('layouts.app')

@section('content')
<h2>My Classes</h2>
<ul>
@foreach($classes as $class)
    <li>
        <a href="{{ route('classes.show', $class->id) }}">{{ $class->name }}</a>
                @if(auth()->user()->role === 'teacher')
            - <a href="{{ route('classes.attendance.create', $class->id) }}">Manage Attendance</a>
        @endif
    </li>
@endforeach
</ul>
@endsection
