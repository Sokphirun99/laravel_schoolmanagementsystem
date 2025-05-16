<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRole extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_roles';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Tell Laravel not to use auto-incrementing for the primary key
     * since we're using a composite key.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Custom method to retrieve records without ordering.
     * This is needed because Laravel might try to order by primary key
     * which can cause issues with composite keys.
     *
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getAllWithoutOrder($columns = ['*'])
    {
        return static::query()->get($columns);
    }

    /**
     * Override newEloquentBuilder to customize query building.
     * This prevents Laravel from automatically adding ORDER BY clauses
     * that might reference non-existent or empty column names.
     *
     * @param \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newEloquentBuilder($query)
    {
        // Use a custom function to modify how ordering is done
        $builder = parent::newEloquentBuilder($query);
        // Remove any default order by clauses
        $builder->macro('defaultOrdering', function ($builder) {
            return $builder;
        });

        return $builder;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'role_id'
    ];

    /**
     * Get the user that has the role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the role assigned to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if a user has a specific role.
     *
     * @param int $userId
     * @param int $roleId
     * @return bool
     */
    public static function hasRole(int $userId, int $roleId): bool
    {
        return self::where('user_id', $userId)
            ->where('role_id', $roleId)
            ->exists();
    }

    /**
     * Assign a role to a user.
     *
     * @param int $userId
     * @param int $roleId
     * @return self
     */
    public static function assignRole(int $userId, int $roleId): self
    {
        return self::firstOrCreate([
            'user_id' => $userId,
            'role_id' => $roleId
        ]);
    }

    /**
     * Remove a role from a user.
     *
     * @param int $userId
     * @param int $roleId
     * @return bool
     */
    public static function removeRole(int $userId, int $roleId): bool
    {
        return (bool) self::where('user_id', $userId)
            ->where('role_id', $roleId)
            ->delete();
    }

    /**
     * Get all users with a specific role.
     *
     * @param int $roleId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getUsersByRole(int $roleId)
    {
        return User::whereHas('roles', function ($query) use ($roleId) {
            $query->where('roles.id', $roleId);
        })->get();
    }

    /**
     * Get all roles for a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRolesForUser(int $userId)
    {
        return Role::whereHas('users', function ($query) use ($userId) {
            $query->where('users.id', $userId);
        })->get();
    }

    /**
     * Override the getQualifiedKeyName method.
     * This helps with issues related to composite keys in Laravel's ORM.
     *
     * @return string
     */
    public function getQualifiedKeyName()
    {
        // Return a qualified column that definitely exists to avoid SQL errors
        return $this->qualifyColumn('user_id');
    }
}
