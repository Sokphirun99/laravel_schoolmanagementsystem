@php
    $user = Auth::guard('portal')->user();
    $userType = $user->user_type;
    $currentRoute = Route::currentRouteName();
@endphp

<div class="space-y-1">
    <!-- Dashboard -->
    <a href="{{ route('portal.dashboard') }}" 
       class="portal-nav-item {{ $currentRoute == 'portal.dashboard' ? 'active' : '' }}">
        <i class="voyager-dashboard"></i>
        Dashboard
    </a>

    @if($userType == 'student')
        <!-- Student Navigation -->
        <div class="mt-6 mb-3">
            <h3 class="px-6 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                Academic
            </h3>
        </div>
        
        <a href="{{ route('portal.student.courses') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'courses') ? 'active' : '' }}">
            <i class="voyager-study"></i>
            My Courses
        </a>
        
        <a href="{{ route('portal.student.assignments') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'assignments') ? 'active' : '' }}">
            <i class="voyager-file-text"></i>
            Assignments
        </a>
        
        <a href="{{ route('portal.student.exams') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'exams') ? 'active' : '' }}">
            <i class="voyager-certificate"></i>
            Exams & Grades
        </a>
        
        <a href="{{ route('portal.student.timetable') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'timetable') ? 'active' : '' }}">
            <i class="voyager-calendar"></i>
            Timetable
        </a>
        
        <a href="{{ route('portal.student.attendance') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'attendance') ? 'active' : '' }}">
            <i class="voyager-check-circle"></i>
            Attendance
        </a>

        <div class="mt-6 mb-3">
            <h3 class="px-6 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                Financial
            </h3>
        </div>
        
        <a href="{{ route('portal.fees.index') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'fees') ? 'active' : '' }}">
            <i class="voyager-dollar"></i>
            Fees & Payments
        </a>

    @elseif($userType == 'parent')
        <!-- Parent Navigation -->
        <div class="mt-6 mb-3">
            <h3 class="px-6 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                Children
            </h3>
        </div>
        
        <a href="{{ route('portal.parent.students') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'students') ? 'active' : '' }}">
            <i class="voyager-group"></i>
            My Children
        </a>
        
        <a href="{{ route('portal.parent.performance') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'performance') ? 'active' : '' }}">
            <i class="voyager-bar-chart"></i>
            Academic Performance
        </a>
        
        <a href="{{ route('portal.parent.attendance') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'attendance') ? 'active' : '' }}">
            <i class="voyager-check-circle"></i>
            Attendance Records
        </a>

        <div class="mt-6 mb-3">
            <h3 class="px-6 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                Financial
            </h3>
        </div>
        
        <a href="{{ route('portal.fees.index') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'fees') ? 'active' : '' }}">
            <i class="voyager-dollar"></i>
            Fees & Payments
        </a>

    @elseif($userType == 'teacher')
        <!-- Teacher Navigation -->
        <div class="mt-6 mb-3">
            <h3 class="px-6 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                Teaching
            </h3>
        </div>
        
        <a href="{{ route('portal.teacher.classes') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'classes') ? 'active' : '' }}">
            <i class="voyager-study"></i>
            My Classes
        </a>
        
        <a href="{{ route('portal.teacher.students') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'students') ? 'active' : '' }}">
            <i class="voyager-group"></i>
            Students
        </a>
        
        <a href="{{ route('portal.teacher.assignments') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'assignments') ? 'active' : '' }}">
            <i class="voyager-file-text"></i>
            Assignments
        </a>
        
        <a href="{{ route('portal.teacher.grades') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'grades') ? 'active' : '' }}">
            <i class="voyager-certificate"></i>
            Grade Management
        </a>
        
        <a href="{{ route('portal.teacher.attendance') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'attendance') ? 'active' : '' }}">
            <i class="voyager-check-circle"></i>
            Attendance
        </a>
        
        <a href="{{ route('portal.teacher.timetable') }}" 
           class="portal-nav-item {{ str_contains($currentRoute, 'timetable') ? 'active' : '' }}">
            <i class="voyager-calendar"></i>
            Timetable
        </a>
    @endif

    <!-- Common Navigation -->
    <div class="mt-6 mb-3">
        <h3 class="px-6 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            Communication
        </h3>
    </div>
    
    <a href="{{ route('portal.announcements.index') }}" 
       class="portal-nav-item {{ str_contains($currentRoute, 'announcements') ? 'active' : '' }}">
        <i class="voyager-sound"></i>
        Announcements
    </a>
    
    <a href="{{ route('portal.communication.index') }}" 
       class="portal-nav-item {{ str_contains($currentRoute, 'communication') ? 'active' : '' }}">
        <i class="voyager-chat"></i>
        Messages
    </a>
    
    <a href="{{ route('portal.events.index') }}" 
       class="portal-nav-item {{ str_contains($currentRoute, 'events') ? 'active' : '' }}">
        <i class="voyager-calendar"></i>
        Events
    </a>

    <div class="mt-6 mb-3">
        <h3 class="px-6 text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">
            Account
        </h3>
    </div>
    
    <a href="{{ route('portal.profile') }}" 
       class="portal-nav-item {{ $currentRoute == 'portal.profile' ? 'active' : '' }}">
        <i class="voyager-person"></i>
        My Profile
    </a>
    
    <a href="{{ route('portal.change-password') }}" 
       class="portal-nav-item {{ $currentRoute == 'portal.change-password' ? 'active' : '' }}">
        <i class="voyager-lock"></i>
        Change Password
    </a>
</div>
