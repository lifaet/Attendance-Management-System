<x-app-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-4">Student Dashboard</h2>

            <!-- Enrolled Classes -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">My Classes</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($classes as $class)
                        <div class="bg-white p-4 rounded-lg shadow">
                            <h4 class="text-xl font-semibold">{{ $class->name }}</h4>
                            <p class="text-gray-600">{{ $class->schedule }}</p>
                            <p class="text-gray-600">Room: {{ $class->room }}</p>
                            
                            <div class="mt-4">
                                <p class="text-sm">Teacher: {{ $class->teacher->name }}</p>
                                
                                <!-- Attendance Stats -->
                                @php
                                    $stats = $class->getAttendanceStats()[$user->id] ?? null;
                                @endphp
                                @if($stats)
                                    <div class="mt-2 grid grid-cols-3 gap-2 text-center">
                                        <div class="bg-green-100 p-2 rounded">
                                            <div class="text-sm font-semibold">Present</div>
                                            <div>{{ $stats['present'] }}</div>
                                        </div>
                                        <div class="bg-red-100 p-2 rounded">
                                            <div class="text-sm font-semibold">Absent</div>
                                            <div>{{ $stats['absent'] }}</div>
                                        </div>
                                        <div class="bg-blue-100 p-2 rounded">
                                            <div class="text-sm font-semibold">Rate</div>
                                            <div>{{ $stats['percentage'] }}%</div>
                                        </div>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <a href="{{ route('classes.show', $class) }}" class="text-blue-600 hover:text-blue-800">
                                        View Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Attendance -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Recent Attendance</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentAttendance as $record)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $record->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $record->class->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($record->status === 'present')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Present
                                            </span>
                                        @elseif($record->status === 'late')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Late
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Absent
                                            </span>
                                        @endif
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