<div class="text-center mb-4">
    <img src="{{ asset('images/school-logo.png') }}" width="100" height="100" alt="School Logo">
    <h4 class="mt-2">School Portal</h4>
</div>

<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/dashboard') ? 'active' : '' }}" href="{{ route('portal.dashboard') }}">
            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
        </a>
    </li>
    
    @if(Auth::guard('portal')->user()->user_type === 'parent')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/students') ? 'active' : '' }}" href="{{ route('portal.students') }}">
            <i class="fas fa-users me-2"></i> My Children
        </a>
    </li>
    @endif
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/grades*') ? 'active' : '' }}" href="{{ route('portal.grades.report') }}">
            <i class="fas fa-graduation-cap me-2"></i> Grades
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/attendance*') ? 'active' : '' }}" href="{{ route('portal.attendance.history') }}">
            <i class="fas fa-calendar-check me-2"></i> Attendance
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/timetable') ? 'active' : '' }}" href="{{ route('portal.timetable') }}">
            <i class="fas fa-calendar-alt me-2"></i> Timetable
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/communication*') ? 'active' : '' }}" href="{{ route('portal.communication.index') }}">
            <i class="fas fa-envelope me-2"></i> Communication
            <span id="unread-messages" class="badge bg-danger rounded-pill"></span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/events*') ? 'active' : '' }}" href="{{ route('portal.events') }}">
            <i class="fas fa-calendar-day me-2"></i> Events
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/fees*') ? 'active' : '' }}" href="{{ route('portal.fees.index') }}">
            <i class="fas fa-money-bill me-2"></i> Fees
        </a>
    </li>
</ul>

<hr class="sidebar-divider">
<div class="text-center mt-3 small">
    <p>© {{ date('Y') }} School Management System</p>
</div>
