<div class="panel widget" style="border-top: 3px solid #2196f3;">
    <div class="panel-heading">
        <h3 class="panel-title">Student Statistics</h3>
    </div>
    <div class="panel-body">
        <div class="row text-center">
            <div class="col-md-4">
                <div class="metric">
                    <span class="icon"><i class="voyager-people"></i></span>
                    <p>
                        <span class="number">{{ $totalCount }}</span>
                        <span class="title">Total Students</span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric">
                    <span class="icon"><i class="voyager-check"></i></span>
                    <p>
                        <span class="number">{{ $activeCount }}</span>
                        <span class="title">Active</span>
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="metric">
                    <span class="icon"><i class="voyager-calendar"></i></span>
                    <p>
                        <span class="number">{{ $newThisMonth }}</span>
                        <span class="title">New This Month</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="text-center">
            <a href="{{ route('voyager.students.index') }}" class="btn btn-sm btn-primary">Manage Students</a>
        </div>
    </div>
</div>
