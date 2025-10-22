<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mark Attendance
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Active Sessions -->
            @if($activeClassSessions->isNotEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold mb-4">Active Classes</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($activeClassSessions as $class)
                                <div class="border rounded-lg p-4">
                                    <h4 class="font-semibold mb-2">{{ $class->name }}</h4>
                                    <p class="text-sm text-gray-600">Teacher: {{ $class->teacher->name }}</p>
                                    <p class="text-sm text-gray-600">Session started: {{ $class->activeSession->started_at->format('H:i') }}</p>
                                    
                                    @php
                                        $existingRecord = $class->attendanceRecords()
                                            ->where('student_id', auth()->id())
                                            ->where('class_session_id', $class->activeSession->id)
                                            ->first();
                                    @endphp

                                    @if($existingRecord)
                                        <div class="mt-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                                @if($existingRecord->status === 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($existingRecord->status === 'present')
                                                    bg-green-100 text-green-800
                                                @else
                                                    bg-red-100 text-red-800
                                                @endif
                                            ">
                                                {{ ucfirst($existingRecord->status) }}
                                                @if($existingRecord->status === 'pending')
                                                    - Awaiting Approval
                                                @endif
                                            </span>
                                        </div>
                                    @else
                                        <form action="{{ route('classes.attendance.student.post', $class) }}" method="POST" class="mt-3">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                                <textarea name="notes" id="notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                                            </div>
                                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                                Mark Present
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <p class="text-gray-600">No active class sessions at the moment.</p>
                    </div>
                </div>
            @endif

            <!-- Recent Attendance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Recent Attendance</h3>
                        <a href="{{ route('student.attendance.stats') }}" class="text-blue-600 hover:text-blue-800">
                            View Full Statistics →
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($recentAttendance as $record)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $record->class->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $record->class->teacher->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $record->created_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($record->status === 'pending')
                                                    bg-yellow-100 text-yellow-800
                                                @elseif($record->status === 'present')
                                                    bg-green-100 text-green-800
                                                @else
                                                    bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($record->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $record->notes ?? '-' }}
                                            @if($record->approval_notes)
                                                <br><span class="text-xs italic">Teacher: {{ $record->approval_notes }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No attendance records found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
