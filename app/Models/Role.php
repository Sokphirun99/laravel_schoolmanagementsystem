<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use SoftDeletes;

    /**
     * Role constants for easy reference
     */
    public const ADMIN = 1;
    public const TEACHER = 2;
    public const STUDENT = 3;
    public const PARENT = 4;
    public const LIBRARIAN = 5;
    public const ACCOUNTANT = 6;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Get the users that belong to this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles', 'role_id', 'user_id');
    }

    /**
     * Get the permissions that belong to this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(\TCG\Voyager\Models\Permission::class, 'permission_role', 'role_id', 'permission_id');
    }
}
