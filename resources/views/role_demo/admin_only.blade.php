@extends('voyager::master')

@section('page_title', 'Admin Only Area')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-lock"></i> Admin Only Area
        </h1>
        <a href="{{ route('admin.role-demo') }}" class="btn btn-success btn-add-new">
            <i class="voyager-angle-left"></i> <span>Back to Role Demo</span>
        </a>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Restricted Content</h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-success">
                            <strong>Success!</strong> You have accessed content that is only available to users with the Admin role.
                        </div>

                        <p>This page is protected by the <code>check.role</code> middleware. The route definition is:</p>

<pre>
Route::middleware(['check.role:admin'])->group(function() {
    Route::get('admin-only', function() {
        return view('role_demo.admin_only');
    })->name('admin.role-demo.admin-only');
});
</pre>

                        <p>If a user without the admin role tries to access this page, they will be redirected with an error message.</p>

                        <div class="well">
                            <h4>Test Accounts</h4>
                            <ul>
                                <li><strong>Can access this page:</strong> admin@school.test, multi@school.test (has both admin and teacher roles)</li>
                                <li><strong>Cannot access this page:</strong> teacher@school.test, student@school.test, parent@school.test</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
