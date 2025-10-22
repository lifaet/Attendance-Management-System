<x-app-layout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg p-4">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold">Edit Attendance Record</h2>
                    <div class="mt-2">
                        <p class="text-gray-600">Class: {{ $record->class->name }}</p>
                        <p class="text-gray-600">Student: {{ $record->student->name }}</p>
                        <p class="text-gray-600">Date: {{ $record->created_at->format('F j, Y') }}</p>
                    </div>
                </div>

                <form action="{{ route('attendance.update', $record) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="present" {{ $record->status === 'present' ? 'selected' : '' }}>Present</option>
                                <option value="late" {{ $record->status === 'late' ? 'selected' : '' }}>Late</option>
                                <option value="absent" {{ $record->status === 'absent' ? 'selected' : '' }}>Absent</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea name="notes" rows="3" class="mt-1 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border border-gray-300 rounded-md">{{ $record->notes }}</textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button type="button" onclick="window.history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Record
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>