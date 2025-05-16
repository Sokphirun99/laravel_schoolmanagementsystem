@extends('voyager::master')

@section('page_title', 'Manage User Roles')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-people"></i> Manage User Roles
        </h1>
        <a href="{{ route('voyager.users.create') }}" class="btn btn-success btn-add-new">
            <i class="voyager-plus"></i> <span>Add New User</span>
        </a>
    </div>
@stop

@section('content')
    <div class="page-content browse container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Current Roles</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="label label-info">{{ $role->display_name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-primary edit-roles" data-toggle="modal" data-target="#edit-roles-modal" data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                                <i class="voyager-edit"></i> Edit Roles
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Roles Modal -->
    <div class="modal fade" id="edit-roles-modal" tabindex="-1" role="dialog" aria-labelledby="editRolesModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.update-user-roles') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="editRolesModalTitle">Edit Roles for <span id="user-name"></span></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="roles">Select Roles</label>
                            <select class="form-control select2" id="roles" name="roles[]" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->display_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $(document).ready(function() {
            $('.edit-roles').click(function() {
                var userId = $(this).data('user-id');
                var userName = $(this).data('user-name');

                $('#user_id').val(userId);
                $('#user-name').text(userName);

                // Load the user's current roles
                $.ajax({
                    url: '{{ route("admin.get-user-roles") }}',
                    method: 'GET',
                    data: { user_id: userId },
                    success: function(response) {
                        $('#roles').val(response.role_ids).trigger('change');
                    }
                });
            });

            $('.select2').select2();
        });
    </script>
@stop
