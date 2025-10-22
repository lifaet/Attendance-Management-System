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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($classes->isEmpty())
                        <p class="text-gray-500 text-center">You haven't been assigned to any classes yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($classes as $class)
                                <div class="border rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold">{{ $class->name }}</h3>
                                            <p class="text-sm text-gray-600">Room: {{ $class->room }}</p>
                                            <p class="text-sm text-gray-600">Schedule: {{ $class->schedule }}</p>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <p class="text-sm">
                                            <span class="font-semibold">Students:</span> 
                                            {{ $class->students->count() }}
                                        </p>
                                    </div>

                                    <div class="border-t pt-4">
                                        @if($class->hasActiveSession())
                                            <div class="mb-3">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                    Class In Session
                                                </span>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('classes.attendance.pending', $class) }}" 
                                                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    View Pending Attendance
                                                </a>
                                                <form action="{{ route('classes.sessions.end', $class->activeSession()) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" 
                                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm"
                                                            onclick="return confirm('Are you sure you want to end this class session?')">
                                                        End Session
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <form action="{{ route('classes.sessions.store', $class) }}" method="POST">
                                                @csrf
                                                <button type="submit" 
                                                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                                    Start Class Session
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    <div class="mt-4 pt-4 border-t">
                                        <a href="{{ route('classes.attendance.report', $class) }}" 
                                           class="text-blue-600 hover:text-blue-900 text-sm">
                                            View Attendance Report →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>