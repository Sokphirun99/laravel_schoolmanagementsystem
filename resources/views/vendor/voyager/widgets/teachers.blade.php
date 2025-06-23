<div class="panel widget" style="border-top: 3px solid #4caf50;">
    <div class="panel-heading">
        <h3 class="panel-title">Teacher Statistics</h3>
    </div>
    <div class="panel-body">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="metric">
                    <span class="icon"><i class="voyager-study"></i></span>
                    <p>
                        <span class="number">{{ $totalCount }}</span>
                        <span class="title">Total Teachers</span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric">
                    <span class="icon"><i class="voyager-book"></i></span>
                    <p>
                        <span class="number">{{ $subjectCount }}</span>
                        <span class="title">Subjects</span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric">
                    <span class="icon"><i class="voyager-person"></i></span>
                    <p>
                        <span class="number">{{ $classTeacherCount }}</span>
                        <span class="title">Class Teachers</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('voyager.teachers.index') }}" class="btn btn-sm btn-primary">Manage Teachers</a>
        </div>
    </div>
</div>