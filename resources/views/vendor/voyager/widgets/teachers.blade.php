<div class="bg-white rounded-lg shadow-md">
    <div class="border-b border-gray-200 p-4">
        <h3 class="text-lg font-semibold text-gray-700">Teacher Statistics</h3>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <i class="voyager-study text-4xl text-green-400"></i>
                <p class="mt-2">
                    <span class="block text-2xl font-bold text-gray-800">{{ $totalCount }}</span>
                    <span class="block text-sm text-gray-500">Total Teachers</span>
                </p>
            </div>
            <div>
                <i class="voyager-book text-4xl text-indigo-400"></i>
                <p class="mt-2">
                    <span class="block text-2xl font-bold text-gray-800">{{ $subjectCount }}</span>
                    <span class="block text-sm text-gray-500">Subjects</span>
                </p>
            </div>
            <div>
                <i class="voyager-person text-4xl text-red-400"></i>
                <p class="mt-2">
                    <span class="block text-2xl font-bold text-gray-800">{{ $classTeacherCount }}</span>
                    <span class="block text-sm text-gray-500">Class Teachers</span>
                </p>
            </div>
        </div>
        <hr class="my-4">
        <h4 class="text-md font-semibold text-gray-600 text-center mb-2">Top Teachers</h4>
        <ul class="divide-y divide-gray-200">
            @forelse($topTeachers as $teacher)
                <li class="py-2 flex justify-between items-center">
                    <a href="{{ route('voyager.teachers.show', $teacher->id) }}" class="text-green-600 hover:underline">{{ $teacher->first_name }} {{ $teacher->last_name }}</a>
                    <span class="text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded-full">{{ $teacher->class_teacher_count }} {{ Str::plural('Class', $teacher->class_teacher_count) }}</span>
                </li>
            @empty
                <li class="py-2 text-center text-gray-500">No teachers found.</li>
            @endforelse
        </ul>
        <div class="text-center mt-4">
            <a href="{{ route('voyager.teachers.index') }}" class="inline-block bg-green-500 hover:bg-green-600 text-white text-sm font-semibold py-2 px-4 rounded-lg shadow-sm transition-colors">Manage Teachers</a>
        </div>
    </div>
</div>
