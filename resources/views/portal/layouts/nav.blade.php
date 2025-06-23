<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/dashboard') ? 'active' : '' }}" href="{{ route('portal.dashboard') }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
    </li>
    
    @if(Auth::guard('portal')->user()->user_type === 'parent')
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/students') ? 'active' : '' }}" href="{{ route('portal.students') }}">
            <i class="fas fa-users"></i> My Children
        </a>
    </li>
    @endif
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/grades*') ? 'active' : '' }}" href="{{ route('portal.grades.report') }}">
            <i class="fas fa-graduation-cap"></i> Grades
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/attendance*') ? 'active' : '' }}" href="{{ route('portal.attendance.history') }}">
            <i class="fas fa-calendar-check"></i> Attendance
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/timetable') ? 'active' : '' }}" href="{{ route('portal.timetable') }}">
            <i class="fas fa-calendar-alt"></i> Timetable
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/communication*') ? 'active' : '' }}" href="{{ route('portal.communication.index') }}">
            <i class="fas fa-envelope"></i> Communication
            <span id="unread-messages" class="badge bg-danger rounded-pill ms-2"></span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::is('portal/events*') ? 'active' : '' }}" href="{{ route('portal.events') }}">
            <i class="fas fa-calendar-day"></i> Events
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
