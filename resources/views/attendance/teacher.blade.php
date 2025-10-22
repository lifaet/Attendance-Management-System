@extends('layouts.app')

@section('content')
<h2>Attendance - {{ $class->name }}</h2>

<form method="POST" action="{{ route('classes.attendance.teacher.update', $class->id) }}">
    @csrf
    <table class="table table-bordered">
        <tr>
            <th>Student</th>
            <th>Status</th>
        </tr>
        @foreach($students as $student)
        <tr>
            <td>{{ $student->name }}</td>
            <td>
                <select name="status[{{ $student->id }}]">
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                </select>
            </td>
        </tr>
        @endforeach
    </table>
    <button type="submit" class="btn btn-success">Update Attendance</button>
</form>
@endsection
