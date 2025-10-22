<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Attendance - {{ $class->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Mark Attendance - {{ $class->name }}</h2>
    <form method="POST" action="{{ route('classes.attendance.student', $class->id) }}">
        @csrf
        <ul class="list-group">
            @foreach($students as $student)
                <li class="list-group-item">
                    <input type="checkbox" name="present[]" value="{{ $student->id }}"> {{ $student->name }}
                </li>
            @endforeach
        </ul>
        <button class="btn btn-success mt-3" type="submit">Submit</button>
    </form>
</div>
</body>
</html>
