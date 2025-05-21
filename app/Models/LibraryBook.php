<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class LibraryBook extends Model

{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
    */
    protected $table = 'library_books';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'isbn',
        'author',
        'publisher',
        'publication_year',
        'edition',
        'category_id',
        'rack_number',
        'quantity',
        'available',
        'price',
        'purchase_date',
        'description',
        'school_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'publication_year' => 'integer',
        'quantity' => 'integer',
        'available' => 'integer',
        'price' => 'decimal:2',
        'purchase_date' => 'date',
        'school_id' => 'integer',
    ];

    /**
     * Get the school that owns the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the book issues for this book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class, 'book_id');
    }

    /**
     * Check if the book is available for borrowing.
     *
     * @return bool
     */

     public function isAvailable()
     {
         return $this->available > 0;
     }
    /**
     * * Scope a query to only include available books.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */

     public function scopeAvailable(Builder $query)
     {
            return $query->where('available', '>', 0);
     }
}
