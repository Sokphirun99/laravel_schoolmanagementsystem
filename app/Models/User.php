<?php

namespace App\Models;

use App\Traits\UserRolesTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable, UserRolesTrait;

    const ROLE_ADMIN = 'admin';
    const ROLE_TEACHER = 'teacher';
    const ROLE_STUDENT = 'student';
    const ROLE_PARENT = 'parent';
    const ROLE_STAFF = 'staff';
    
    // Role hierarchy levels for permission checking
    const ROLE_LEVELS = [
        'admin' => 100,
        'staff' => 80,
        'teacher' => 60,
        'parent' => 40,
        'student' => 20,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'status',
        'phone',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'status' => 'boolean',
    ];

    /**
     * Relationships
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function parent()
    {
        return $this->hasOne(ParentModel::class);
    }

    public function notices()
    {
        return $this->hasMany(Notice::class, 'created_by');
    }

    /**
     * Accessors
     */
    public function getProfileTypeAttribute()
    {
        return $this->role;
    }

    public function getFullNameAttribute()
    {
        if ($this->isStudent() && $this->student) {
            return $this->student->full_name;
        } elseif ($this->isTeacher() && $this->teacher) {
            return $this->teacher->first_name . ' ' . $this->teacher->last_name;
        } elseif ($this->isParent() && $this->parent) {
            return $this->parent->full_name;
        }
        
        return $this->name;
    }

    public function getProfilePhotoAttribute()
    {
        if ($this->isStudent() && $this->student && $this->student->photo) {
            return $this->student->photo;
        } elseif ($this->isTeacher() && $this->teacher && $this->teacher->photo) {
            return $this->teacher->photo;
        } elseif ($this->isParent() && $this->parent && $this->parent->photo) {
            return $this->parent->photo;
        }
        
        return $this->avatar ?: 'users/default.png';
    }

    /**
     * Check user roles
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isTeacher()
    {
        return $this->role === self::ROLE_TEACHER;
    }

    public function isStudent()
    {
        return $this->role === self::ROLE_STUDENT;
    }

    public function isParent()
    {
        return $this->role === self::ROLE_PARENT;
    }

    public function isStaff()
    {
        return $this->role === self::ROLE_STAFF;
    }
    
    /**
     * Check if user has permission based on role hierarchy
     */
    public function hasRoleLevel($requiredLevel)
    {
        $userLevel = self::ROLE_LEVELS[$this->role] ?? 0;
        return $userLevel >= $requiredLevel;
    }
    
    /**
     * Check if user can manage another user based on role hierarchy
     */
    public function canManage(User $user)
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        $thisLevel = self::ROLE_LEVELS[$this->role] ?? 0;
        $targetLevel = self::ROLE_LEVELS[$user->role] ?? 0;
        
        return $thisLevel > $targetLevel;
    }
    
    /**
     * Get user's role priority/level
     */
    public function getRoleLevel()
    {
        return self::ROLE_LEVELS[$this->role] ?? 0;
    }

    /**
     * Scopes
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', self::ROLE_ADMIN);
    }

    public function scopeTeachers($query)
    {
        return $query->where('role', self::ROLE_TEACHER);
    }

    public function scopeStudents($query)
    {
        return $query->where('role', self::ROLE_STUDENT);
    }

    public function scopeParents($query)
    {
        return $query->where('role', self::ROLE_PARENT);
    }

    /**
     * Record last login
     */
    public function recordLogin()
    {
        $this->last_login_at = Carbon::now();
        $this->last_login_ip = request()->ip();
        return $this->save();
    }

    /**
     * Get related profile data
     */
    public function getProfileData()
    {
        if ($this->isStudent()) {
            return $this->student;
        } elseif ($this->isTeacher()) {
            return $this->teacher;
        } elseif ($this->isParent()) {
            return $this->parent;
        }
        
        return null;
    }
}
