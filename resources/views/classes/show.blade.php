<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg">
                <div class="p-4">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">{{ $class->name }}</h2>
                        @can('takeAttendance', $class)
                            <div class="space-x-4">
                                @if($class->hasActiveSession())
                                    <div class="flex items-center space-x-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            Class in Session
                                        </span>
                                        <a href="{{ route('classes.attendance.pending', $class) }}" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            View Pending Attendance
                                        </a>
                                        <form action="{{ route('classes.sessions.end', ['session' => $class->activeSession()]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                End Session
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <form action="{{ route('classes.sessions.store', $class) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            Start Class Session
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endcan
                    </div>

                    <div class="mb-6">
                        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                            <div class="px-4 py-5 sm:px-6">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Class Information</h3>
                            </div>
                            <div class="border-t border-gray-200">
                                <dl>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Teacher</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $class->teacher->name }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Schedule</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $class->schedule }}</dd>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Room</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $class->room }}</dd>
                                    </div>
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $class->description }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Students ({{ $class->students->count() }})</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($class->students as $student)
                                @php
                                    $stats = $class->getAttendanceStats()[$student->id] ?? null;
                                @endphp
                                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                                    <div class="px-4 py-4">
                                        <h4 class="text-lg font-semibold">{{ $student->name }}</h4>
                                        @if($stats)
                                            <div class="mt-2 grid grid-cols-3 gap-2">
                                                <div class="text-center bg-green-100 rounded p-2">
                                                    <span class="text-sm text-green-800">Present</span>
                                                    <div class="font-bold">{{ $stats['present'] }}</div>
                                                </div>
                                                <div class="text-center bg-red-100 rounded p-2">
                                                    <span class="text-sm text-red-800">Absent</span>
                                                    <div class="font-bold">{{ $stats['absent'] }}</div>
                                                </div>
                                                <div class="text-center bg-blue-100 rounded p-2">
                                                    <span class="text-sm text-blue-800">Rate</span>
                                                    <div class="font-bold">{{ $stats['percentage'] }}%</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Attendance</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($attendanceSummary as $summary)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $summary['date']->format('M d, Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $summary['present'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $summary['late'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $summary['absent'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="#" class="text-indigo-600 hover:text-indigo-900">View Details</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
