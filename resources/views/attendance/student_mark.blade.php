<x-app-layout>
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold">Mark Attendance - {{ $class->name }}</h2>
                <div class="text-sm text-gray-500">{{ $class->schedule }} • {{ $class->room }}</div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($class->students as $student)
                    <div class="flex items-center justify-between p-4 border rounded">
                        <div>
                            <div class="font-medium">{{ $student->name }}</div>
                            <div class="text-sm text-gray-500">{{ $student->email }}</div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button data-student="{{ $student->id }}" data-status="present" class="mark-btn inline-flex items-center px-3 py-1 rounded bg-green-100 text-green-800 hover:bg-green-200">Present</button>
                            <button data-student="{{ $student->id }}" data-status="late" class="mark-btn inline-flex items-center px-3 py-1 rounded bg-yellow-100 text-yellow-800 hover:bg-yellow-200">Late</button>
                            <button data-student="{{ $student->id }}" data-status="absent" class="mark-btn inline-flex items-center px-3 py-1 rounded bg-red-100 text-red-800 hover:bg-red-200">Absent</button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="attendance-alert" class="mt-6 hidden p-3 rounded text-sm"></div>
        </div>
    </div>
</x-app-layout>
<script>
    window.Laravel = window.Laravel || {};
    window.Laravel.classId = {{ $class->id }};
</script>