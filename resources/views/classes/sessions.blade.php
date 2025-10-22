<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $class->name }} - Class Sessions
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Active Session Card -->
            @if($activeSession = $class->activeSession())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold">Active Session</h3>
                                <p class="text-sm text-gray-600">Started: {{ $activeSession->started_at->format('M d, Y H:i') }}</p>
                            </div>
                            <form action="{{ route('classes.sessions.end', $activeSession) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    End Session
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <form action="{{ route('classes.sessions.store', $class) }}" method="POST" class="mb-6">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Start New Session
                    </button>
                </form>
            @endif

            <!-- Past Sessions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Past Sessions</h3>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Started At
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Ended At
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Duration
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Attendance
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($sessions as $session)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $session->started_at->format('M d, Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $session->ended_at ? $session->ended_at->format('M d, Y H:i') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($session->ended_at)
                                                {{ $session->started_at->diffForHumans($session->ended_at, true) }}
                                            @else
                                                {{ $session->started_at->diffForHumans(now(), true) }} (Ongoing)
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            {{ $session->attendanceRecords()->where('status', 'present')->count() }}/{{ $class->students->count() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <a href="{{ route('classes.sessions.show', $session) }}" class="text-blue-600 hover:text-blue-800">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $sessions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>