<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'max_points',
        'due_date',
        'assignment_type',
        'weight',
    ];

    protected $casts = [
        'due_date' => 'date',
        'max_points' => 'decimal:2',
        'weight' => 'decimal:2',
    ];

    /**
     * Get the course this assignment belongs to.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the grades for this assignment.
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
