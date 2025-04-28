<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FeeType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'is_recurring', 'frequency'
    ];

    protected $casts = [
        'is_recurring' => 'boolean',
    ];

    /**
     * The valid fee frequencies
     */
    const FREQUENCY_MONTHLY = 'monthly';
    const FREQUENCY_QUARTERLY = 'quarterly';
    const FREQUENCY_ANNUALLY = 'annually';

    /**
     * Get all valid fee frequencies
     */
    public static function getFrequencies()
    {
        return [
            self::FREQUENCY_MONTHLY,
            self::FREQUENCY_QUARTERLY,
            self::FREQUENCY_ANNUALLY
        ];
    }

    /**
     * Get the fee structures for this fee type
     */
    public function feeStructures()
    {
        return $this->hasMany(FeeStructure::class);
    }
}
