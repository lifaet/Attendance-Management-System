<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Class Management
            </h2>
            <a href="{{ route('classes.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Create New Class
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($classes->isEmpty())
                        <p class="text-gray-500 text-center py-4">No classes found. Create a new class to get started.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Schedule</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($classes as $class)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $class->name }}</div>
                                                <div class="text-sm text-gray-500">Room: {{ $class->room }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($class->teacher)
                                                    <div class="text-sm text-gray-900">{{ $class->teacher->name }}</div>
                                                @else
                                                    <form action="{{ route('classes.assign.teacher', $class) }}" method="POST" class="flex items-center space-x-2">
                                                        @csrf
                                                        <select name="teacher_id" class="text-sm rounded-md border-gray-300">
                                                            <option value="">Select Teacher</option>
                                                            @foreach($teachers as $teacher)
                                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-xs">
                                                            Assign
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $class->schedule }}
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="text-sm text-gray-900">{{ $class->students->count() }} students</div>
                                                <button onclick="openStudentModal('{{ $class->id }}')" class="text-blue-600 hover:text-blue-900 text-xs">
                                                    Manage Students
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                                <a href="{{ route('classes.show', $class) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                <a href="{{ route('classes.edit', $class) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('classes.destroy', $class) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this class?')">
                                                        Delete
                                                    </button>
                                                </form>
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

    <!-- Student Assignment Modal -->
    <div id="studentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-2">Manage Students</h3>
                <form id="assignStudentsForm" method="POST">
                    @csrf
                    <div class="mt-2">
                        <select name="student_ids[]" multiple class="w-full rounded-md border-gray-300" size="10">
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end space-x-3 mt-4">
                        <button type="button" onclick="closeStudentModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openStudentModal(classId) {
            document.getElementById('studentModal').classList.remove('hidden');
            document.getElementById('assignStudentsForm').action = `/classes/${classId}/assign-students`;
        }

        function closeStudentModal() {
            document.getElementById('studentModal').classList.add('hidden');
        }
    </script>
</x-app-layout>