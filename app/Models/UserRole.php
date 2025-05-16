<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * UserRole model representing the many-to-many relationship between users and roles.
 *
 * IMPORTANT: This model uses a composite primary key (user_id, role_id) in the database,
 * but since Laravel doesn't fully support composite keys in Eloquent, we've implemented
 * several workarounds to avoid SQL errors related to ordering and primary keys.
 *
 * Updated: May 17, 2025
 */
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
     * The primary key for the model.
     * While the actual primary key in the database is composite,
     * we'll use user_id for Laravel's internal references.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The type of key for the model.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Tell Laravel not to use auto-incrementing for the primary key.
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
        // Use raw query to avoid ordering issues
        $count = self::whereRaw('user_id = ? AND role_id = ?', [$userId, $roleId])->count();
        return $count > 0;
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
        // Use method that won't try ordering by id
        $existingRole = self::where('user_id', $userId)
            ->where('role_id', $roleId)
            ->first();

        if ($existingRole) {
            return $existingRole;
        }

        return self::create([
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

    /**
     * Get the route key name for Laravel's route model binding.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'user_id';
    }

    /**
     * Override the find method to handle composite keys properly.
     *
     * @param mixed $id User ID
     * @param array $columns Columns to select
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public static function findWithRoleId($userId, $roleId, $columns = ['*'])
    {
        return static::where('user_id', $userId)
            ->where('role_id', $roleId)
            ->first($columns);
    }

    /**
     * Cast the model to an array - important for serialization.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        // Ensure the array has a meaningful ID for JSON serialization
        if (!isset($array['id']) && isset($array['user_id']) && isset($array['role_id'])) {
            $array['id'] = $array['user_id'] . '_' . $array['role_id'];
        }

        return $array;
    }
}
