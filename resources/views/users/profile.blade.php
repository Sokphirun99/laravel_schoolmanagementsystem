@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card" style="box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; border: none;">
                <div class="card-header" style="background: linear-gradient(135deg, var(--voyager-primary, #22A7F0), #1989d8); color: white; padding: 15px 20px; position: relative; border-bottom: none;">
                    <h5 class="mb-0" style="font-weight: 600; display: flex; align-items: center;">
                        <i class="fas fa-user me-2" style="background: rgba(255,255,255,0.2); width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 10px;"></i>
                        {{ __('My Profile') }}
                    </h5>
                </div>

                <div class="card-body" style="padding: 25px 20px;">
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <div class="mb-3" style="position: relative;">
                                    <div class="avatar-container" style="position: relative; width: 150px; height: 150px; margin: 0 auto; border-radius: 50%; box-shadow: 0 5px 15px rgba(0,0,0,0.1); overflow: hidden; border: 3px solid var(--voyager-primary, #22A7F0);">
                                        @if ($user->avatar)
                                            <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('storage/users/default.png') }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        @endif
                                        <div class="avatar-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(34, 167, 240, 0.7); color: white; font-size: 0.8em; text-align: center; padding: 4px 0; transform: translateY(100%); transition: all 0.3s ease;">
                                            <i class="fas fa-camera"></i> Change
                                        </div>
                                    </div>
                                    <script>
                                        document.querySelector('.avatar-container').addEventListener('mouseenter', function() {
                                            this.querySelector('.avatar-overlay').style.transform = 'translateY(0)';
                                        });
                                        document.querySelector('.avatar-container').addEventListener('mouseleave', function() {
                                            this.querySelector('.avatar-overlay').style.transform = 'translateY(100%)';
                                        });
                                    </script>
                                </div>
                                <div class="mb-3">
                                    <label for="avatar" class="form-label" style="color: var(--voyager-primary, #22A7F0); font-weight: 600; display: block; text-align: center; margin-top: 10px;">
                                        <i class="fas fa-image" style="margin-right: 5px;"></i>{{ __('Change Profile Picture') }}
                                    </label>
                                    <input type="file" class="form-control @error('avatar') is-invalid @enderror" id="avatar" name="avatar" style="display: none;">
                                    <div class="d-flex justify-content-center">
                                        <button type="button" class="btn btn-sm" style="background-color: #f8f9fa; border: 2px solid #e9ecef; color: #6c757d; border-radius: 20px; padding: 5px 15px; font-size: 0.85em; cursor: pointer;" onclick="document.getElementById('avatar').click()">
                                            <i class="fas fa-upload me-1"></i> Select New Image
                                        </button>
                                    </div>
                                    <div id="selected-file" class="small text-center mt-2" style="color: #6c757d;"></div>
                                    @error('avatar')
                                        <div class="text-danger small text-center mt-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                    <script>
                                        document.getElementById('avatar').addEventListener('change', function(e) {
                                            const fileName = e.target.files[0] ? e.target.files[0].name : 'No file selected';
                                            document.getElementById('selected-file').textContent = fileName;
                                        });
                                    </script>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-4">
                                    <label for="name" class="form-label" style="color: var(--voyager-primary, #22A7F0); font-weight: 600; margin-bottom: 10px; display: block;">
                                        <i class="fas fa-user" style="background: rgba(34, 167, 240, 0.1); width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 8px;"></i>
                                        {{ __('Full Name') }}
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; color: var(--voyager-primary, #22A7F0);">
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0; padding: 10px 15px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                    </div>
                                    @error('name')
                                        <div class="text-danger mt-2" style="font-size: 0.9em;">
                                            <i class="fas fa-exclamation-circle" style="margin-right: 5px;"></i>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label" style="color: var(--voyager-primary, #22A7F0); font-weight: 600; margin-bottom: 10px; display: block;">
                                        <i class="fas fa-envelope" style="background: rgba(34, 167, 240, 0.1); width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; margin-right: 8px;"></i>
                                        {{ __('Email Address') }}
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text" style="background: #f8f9fa; border: 2px solid #e9ecef; border-right: none; color: var(--voyager-primary, #22A7F0);">
                                            <i class="fas fa-at"></i>
                                        </span>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email" style="border: 2px solid #e9ecef; border-left: none; border-radius: 0 8px 8px 0; padding: 10px 15px; transition: all 0.3s ease; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">{{ __('Role') }}</label>
                                    <input type="text" class="form-control" value="{{ $user->roleText() }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Role-specific fields -->
                        @if($user->isTeacher() && $user->teacher)
                        <div class="card mb-3">
                            <div class="card-header">{{ __('Teacher Information') }}</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user->teacher->first_name) }}">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user->teacher->last_name) }}">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="qualification" class="form-label">{{ __('Qualification') }}</label>
                                        <input id="qualification" type="text" class="form-control @error('qualification') is-invalid @enderror" name="qualification" value="{{ old('qualification', $user->teacher->qualification) }}">
                                        @error('qualification')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="experience" class="form-label">{{ __('Experience') }}</label>
                                        <input id="experience" type="text" class="form-control @error('experience') is-invalid @enderror" name="experience" value="{{ old('experience', $user->teacher->experience) }}">
                                        @error('experience')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($user->isStudent() && $user->student)
                        <div class="card mb-3">
                            <div class="card-header">{{ __('Student Information') }}</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user->student->first_name) }}">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user->student->last_name) }}">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Class') }}</label>
                                        <input type="text" class="form-control" value="{{ $user->student->class ? $user->student->class->name : 'Not Assigned' }}" readonly>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Section') }}</label>
                                        <input type="text" class="form-control" value="{{ $user->student->section ? $user->student->section->name : 'Not Assigned' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @elseif($user->isParent() && $user->parent)
                        <div class="card mb-3">
                            <div class="card-header">{{ __('Parent Information') }}</div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name" class="form-label">{{ __('First Name') }}</label>
                                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name', $user->parent->first_name) }}">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name" class="form-label">{{ __('Last Name') }}</label>
                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name', $user->parent->last_name) }}">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="occupation" class="form-label">{{ __('Occupation') }}</label>
                                        <input id="occupation" type="text" class="form-control @error('occupation') is-invalid @enderror" name="occupation" value="{{ old('occupation', $user->parent->occupation) }}">
                                        @error('occupation')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                
                                @if($user->parent->students && $user->parent->students->count() > 0)
                                <div class="mt-4">
                                    <h6>{{ __('Children') }}</h6>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Class</th>
                                                <th>Section</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->parent->students as $student)
                                            <tr>
                                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                                <td>{{ $student->class ? $student->class->name : 'Not Assigned' }}</td>
                                                <td>{{ $student->section ? $student->section->name : 'Not Assigned' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <div class="row mb-0">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>{{ __('Update Profile') }}
                                </button>
                                <a href="{{ route('profile.password') }}" class="btn btn-secondary">
                                    <i class="fas fa-key me-2"></i>{{ __('Change Password') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
