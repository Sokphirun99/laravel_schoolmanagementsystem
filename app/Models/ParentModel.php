<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'occupation',
        'gender',
        'status',
        'photo',
        'user_id'
    ];
    
    protected $casts = [
        'status' => 'boolean',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
    
    // Accessor for full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}