<div class="panel widget center bgimage" style="background-image:url('{{ Voyager::image(config('voyager.widgets.grade-summary.image', 'widgets/grade-summary.jpg')) }}'); background-size: cover; background-position: center center; min-height: 300px;">
    <div class="dimmer"></div>
    <div class="panel-content">
        <i class="{{ $icon ?? 'voyager-receipt' }}" style="font-size: 56px;"></i>
        <h4>{!! $title ?? 'Gradebook Summary' !!}</h4>
        <p>{{ $courseCount }} Courses - {{ $assignmentCount }} Assignments - {{ $gradeCount }} Grades</p>
        <a href="{{ route('grades.index') }}" class="btn btn-primary">View All Courses</a>
    </div>
</div>

<!-- Recent Grades -->
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Recent Grades</h3>
    </div>
    <div class="panel-body" style="max-height: 300px; overflow-y: auto;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Assignment</th>
                    <th>Grade</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentGrades as $grade)
                    <tr>
                        <td>{{ $grade->student->name ?? 'Student #' . $grade->student_id }}</td>
                        <td>{{ $grade->assignment->title ?? 'Assignment #' . $grade->assignment_id }}</td>
                        <td>{{ $grade->points_earned }} / {{ $grade->assignment->max_points ?? 'N/A' }}</td>
                        <td>{{ $grade->updated_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No recent grades found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Course Stats -->
@if(count($courseStats) > 0)
<div class="panel">
    <div class="panel-heading">
        <h3 class="panel-title">Course Statistics</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            @foreach($courseStats as $stat)
                <div class="col-md-4">
                    <div class="panel panel-bordered panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ $stat['course']->name }}</h3>
                        </div>
                        <div class="panel-body">
                            <ul class="list-unstyled">
                                <li><strong>Average:</strong> {{ $stat['average_score'] }}%</li>
                                <li><strong>Students:</strong> {{ $stat['student_count'] }}</li>
                                <li>
                                    <strong>Distribution:</strong>
                                    @foreach($stat['grade_distribution'] as $grade => $count)
                                        <span class="label label-{{ $grade === 'A' ? 'success' : ($grade === 'F' ? 'danger' : 'primary') }}">
                                            {{ $grade }}: {{ $count }}
                                        </span>
                                    @endforeach
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
