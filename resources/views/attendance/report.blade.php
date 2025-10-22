<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Attendance Report - {{ $class->name }}</h2>
                        <a href="{{ route('classes.show', $class) }}" class="text-blue-600 hover:text-blue-800">
                            ← Back to Class
                        </a>
                    </div>

                    <!-- Summary Statistics -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">Summary (Last 7 Days)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($stats as $day)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium">{{ \Carbon\Carbon::parse($day->date)->format('M d, Y') }}</h4>
                                    <div class="mt-2 space-y-1">
                                        <p class="text-sm text-gray-600">
                                            Present: <span class="font-medium text-green-600">{{ $day->present }}</span>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Absent: <span class="font-medium text-red-600">{{ $day->absent }}</span>
                                        </p>
                                        <p class="text-sm text-gray-600">
                                            Late: <span class="font-medium text-yellow-600">{{ $day->late }}</span>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Individual Student Records -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Individual Records</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Present
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Absent
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Late
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Attendance Rate
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($students as $student)
                                        @php
                                            $studentRecords = $records[$student->id] ?? collect();
                                            $total = $studentRecords->count();
                                            $present = $studentRecords->where('status', 'present')->count();
                                            $absent = $studentRecords->where('status', 'absent')->count();
                                            $late = $studentRecords->where('status', 'late')->count();
                                            $rate = $total ? round((($present + $late) / $total) * 100) : 0;
                                        @endphp
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-green-600">{{ $present }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-red-600">{{ $absent }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-yellow-600">{{ $late }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium 
                                                    @if($rate >= 90) text-green-600
                                                    @elseif($rate >= 75) text-yellow-600
                                                    @else text-red-600
                                                    @endif">
                                                    {{ $rate }}%
                                                </div>
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