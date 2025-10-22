<x-app-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-4">Teacher Dashboard</h2>

            <!-- Today's Classes -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Today's Classes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($classes as $class)
                        <div class="bg-white p-4 rounded-lg shadow">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="text-xl font-semibold">{{ $class->name }}</h4>
                                    <p class="text-gray-600">{{ $class->schedule }}</p>
                                    <p class="text-gray-600">Room: {{ $class->room }}</p>
                                </div>
                                    <a href="{{ route('classes.attendance.create', $class) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Take Attendance
                                </a>
                            </div>
                            
                            <div class="mt-4">
                                <p class="text-sm text-gray-600">{{ $class->students->count() }} Students</p>
                                <div class="mt-2">
                                    <a href="{{ route('classes.show', $class) }}" class="text-blue-600 hover:text-blue-800">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Attendance Records -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Recent Attendance Records</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present/Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentAttendance as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $record->class->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ date('M d, Y', strtotime($record->date)) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $record->present_count }}/{{ $record->total_count }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('classes.show', $record->class->id) }}" class="text-blue-600 hover:text-blue-800">
                                            View Class
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>