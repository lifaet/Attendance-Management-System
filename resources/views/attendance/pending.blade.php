<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold">Pending Attendance - {{ $class->name }}</h2>
                        <a href="{{ route('classes.show', $class) }}" class="text-blue-600 hover:text-blue-800">
                            ← Back to Class
                        </a>
                    </div>

                    @if($pendingRecords->isEmpty())
                        <div class="text-gray-500 text-center py-4">
                            No pending attendance records for today.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Time
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pendingRecords as $record)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $record->student->name }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @if($record->status === 'present')
                                                        bg-green-100 text-green-800
                                                    @elseif($record->status === 'late')
                                                        bg-yellow-100 text-yellow-800
                                                    @else
                                                        bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($record->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $record->created_at->format('h:i A') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-3">
                                                    <form action="{{ route('classes.attendance.approve', ['class' => $class, 'record' => $record]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" 
                                                            class="text-green-600 hover:text-green-900"
                                                            onclick="return confirm('Are you sure you want to approve this attendance record?')">
                                                            Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('classes.attendance.reject', ['class' => $class, 'record' => $record]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" 
                                                            class="text-red-600 hover:text-red-900"
                                                            onclick="return confirm('Are you sure you want to reject this attendance record?')">
                                                            Reject
                                                        </button>
                                                    </form>
                                                </div>
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
</x-app-layout>