<x-app-layout>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total Students</h3>
                    <div class="flex items-center">
                        <div class="text-3xl font-bold">{{ $stats['students'] }}</div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total Teachers</h3>
                    <div class="flex items-center">
                        <div class="text-3xl font-bold">{{ $stats['teachers'] }}</div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 text-sm">Total Classes</h3>
                    <div class="flex items-center">
                        <div class="text-3xl font-bold">{{ $stats['classes'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('users.create') }}" class="block w-full text-center bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Add New User
                        </a>
                        <a href="{{ route('classes.create') }}" class="block w-full text-center bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            Create New Class
                        </a>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-lg font-semibold mb-4">System Overview</h3>
                    <div class="space-y-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Today's Attendance Rate</h4>
                            <div class="text-2xl font-bold">{{ $stats['todayAttendanceRate'] }}%</div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Active Classes Today</h4>
                            <div class="text-2xl font-bold">{{ $stats['activeClasses'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Recent Activity</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($recentActivity as $activity)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity->created_at->diffForHumans() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $activity->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity->details }}
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