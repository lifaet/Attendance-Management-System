<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Classes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Active Sessions -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Active Classes</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        @php
                            $activeClasses = $classes->filter(function($class) {
                                return $class->hasActiveSession();
                            });
                        @endphp

                        @if($activeClasses->isEmpty())
                            <p class="text-gray-500 text-center">No active class sessions at the moment.</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($activeClasses as $class)
                                    <div class="border rounded-lg p-4">
                                            <h4 class="font-semibold mb-2">{{ $class->name }}</h4>
                                            <p class="text-sm text-gray-600">Teacher: {{ $class->teacher->name }}</p>
                                            @php $session = $class->activeSession(); @endphp
                                            @if($session)
                                                <p class="text-sm text-gray-600">Started: {{ $session->started_at->format('H:i') }}</p>

                                                @php
                                                    $attendance = $class->attendanceRecords()
                                                        ->where('student_id', auth()->id())
                                                        ->where('class_session_id', $session->id)
                                                        ->first();
                                                @endphp
                                            @else
                                                @php $attendance = null; @endphp
                                            @endif

                                        <div class="mt-4">
                                            @if($attendance)
                                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                    @if($attendance->status === 'pending')
                                                        bg-yellow-100 text-yellow-800
                                                    @elseif($attendance->status === 'present')
                                                        bg-green-100 text-green-800
                                                    @else
                                                        bg-red-100 text-red-800
                                                    @endif">
                                                    Status: {{ ucfirst($attendance->status) }}
                                                </div>
                                            @else
                                                <form action="{{ route('classes.attendance.mark', $class) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                        Mark Present
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- All Classes -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">All My Classes</h3>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        @if($classes->isEmpty())
                            <p class="text-gray-500 text-center">You are not enrolled in any classes.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($classes as $class)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                                                    <div class="text-sm text-gray-500">Room: {{ $class->room }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $class->teacher->name }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $class->schedule }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('classes.attendance.student.report', $class) }}" class="text-blue-600 hover:text-blue-900">
                                                        View Report
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>