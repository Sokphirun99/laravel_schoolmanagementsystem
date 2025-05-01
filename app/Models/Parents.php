<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class Parents extends Model
{
    use SoftDeletes;

    protected $table = 'parents';

    protected $fillable = [
        'user_id', 'father_name', 'mother_name', 'father_occupation',
        'mother_occupation', 'father_phone', 'mother_phone', 'father_email',
        'mother_email', 'address', 'email'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_name', 'primary_contact', 'primary_email'];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::creating(function ($parent) {
            // If email is not set but we have father/mother email, use one of them
            if (empty($parent->email) && (!empty($parent->father_email) || !empty($parent->mother_email))) {
                $parent->email = $parent->father_email ?: $parent->mother_email;
            }
        });
    }

    /**
     * Get the user associated with the parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the students associated with the parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    /**
     * Get the parent's full name (both father and mother).
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->father_name} & {$this->mother_name}",
        );
    }

    /**
     * Get primary contact number.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function primaryContact(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->father_phone ?: $this->mother_phone,
        );
    }

    /**
     * Get primary email address.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function primaryEmail(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->email ?: ($this->father_email ?: $this->mother_email),
        );
    }

    /**
     * Scope a query to only include parents with at least one student.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStudents(Builder $query): Builder
    {
        return $query->whereHas('students');
    }

    /**
     * Get all active students for this parent.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveStudents()
    {
        return $this->students()->where('status', 'active')->get();
    }

    /**
     * Check if parent has any student in a particular class.
     *
     * @param int $classId
     * @return bool
     */
    public function hasStudentInClass($classId): bool
    {
        return $this->students()->where('class_id', $classId)->exists();
    }
}
