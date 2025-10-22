<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg p-4">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">Take Attendance - {{ $class->name }}</h2>
                    <p class="text-gray-600">{{ $class->schedule }} - {{ $class->room }}</p>
                </div>

                <form action="{{ route('classes.attendance.store', $class) }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($class->students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $student->email }}</div>
                                            <input type="hidden" name="attendance[{{ $loop->index }}][student_id]" value="{{ $student->id }}">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="attendance[{{ $loop->index }}][status]" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                @php
                                                    $currentStatus = $existingAttendance[$student->id]->status ?? null;
                                                @endphp
                                                <option value="present" {{ $currentStatus === 'present' ? 'selected' : '' }}>Present</option>
                                                <option value="late" {{ $currentStatus === 'late' ? 'selected' : '' }}>Late</option>
                                                <option value="absent" {{ $currentStatus === 'absent' ? 'selected' : '' }}>Absent</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" name="attendance[{{ $loop->index }}][notes]" 
                                                value="{{ $existingAttendance[$student->id]->notes ?? '' }}"
                                                class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                                                placeholder="Optional notes">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="window.history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Attendance
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>