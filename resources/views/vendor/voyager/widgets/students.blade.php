<div class="bg-white rounded-lg shadow-md">
    <div class="border-b border-gray-200 p-4">
        <h3 class="text-lg font-semibold text-gray-700">Student Statistics</h3>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <i class="voyager-people text-4xl text-blue-400"></i>
                <p class="mt-2">
                    <span class="block text-2xl font-bold text-gray-800">{{ $totalCount }}</span>
                    <span class="block text-sm text-gray-500">Total Students</span>
                </p>
            </div>
            <div>
                <i class="voyager-check text-4xl text-green-400"></i>
                <p class="mt-2">
                    <span class="block text-2xl font-bold text-gray-800">{{ $activeCount }}</span>
                    <span class="block text-sm text-gray-500">Active</span>
                </p>
            </div>
            <div>
                <i class="voyager-calendar text-4xl text-purple-400"></i>
                <p class="mt-2">
                    <span class="block text-2xl font-bold text-gray-800">{{ $newThisMonth }}</span>
                    <span class="block text-sm text-gray-500">New This Month</span>
                </p>
            </div>
        </div>
        <hr class="my-4">
        <h4 class="text-md font-semibold text-gray-600 text-center mb-2">Recent Enrollments</h4>
        <ul class="divide-y divide-gray-200">
            @forelse($recentStudents as $student)
                <li class="py-2 flex justify-between items-center">
                    <a href="{{ route('voyager.students.show', $student->id) }}" class="text-blue-600 hover:underline">{{ $student->first_name }} {{ $student->last_name }}</a>
                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">{{ $student->created_at->format('M d') }}</span>
                </li>
            @empty
                <li class="py-2 text-center text-gray-500">No recent students found.</li>
            @endforelse
        </ul>
        <div class="text-center mt-4">
            <a href="{{ route('voyager.students.index') }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors">Manage Students</a>
        </div>
    </div>
</div>
