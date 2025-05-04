<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use App\Models\BookCategory;

class Book extends Model
{
    use SoftDeletes;

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
        'category_id',
        'description',
        'publication_year',
        'edition',
        'total_quantity',
        'available_quantity',
        'shelf_location',
        'cover_image',
        'price',
        'language',
        'pages',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'publication_year' => 'integer',
        'total_quantity' => 'integer',
        'available_quantity' => 'integer',
        'price' => 'float',
        'pages' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Set available_quantity equal to total_quantity when creating a new book
        static::creating(function ($book) {
            if (!isset($book->available_quantity)) {
                $book->available_quantity = $book->total_quantity;
            }
        });
    }

    /**
     * Get the category that owns the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }

    /**
     * Get the book issues for the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookIssues()
    {
        return $this->hasMany(BookIssue::class);
    }

    /**
     * Check if book is available for issue.
     *
     * @return bool
     */
    public function isAvailable()
    {
        return $this->available_quantity > 0;
    }

    /**
     * Scope a query to only include available books.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable(Builder $query)
    {
        return $query->where('available_quantity', '>', 0);
    }

    /**
     * Scope a query to filter books by category.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int  $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByCategory(Builder $query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope a query to include a search across book fields.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'LIKE', "%{$search}%")
              ->orWhere('author', 'LIKE', "%{$search}%")
              ->orWhere('publisher', 'LIKE', "%{$search}%")
              ->orWhere('isbn', 'LIKE', "%{$search}%");
        });
    }

    /**
     * Issue a book to a student or teacher.
     *
     * @param int $studentId
     * @param int|null $teacherId
     * @param string $returnDate
     * @return BookIssue|false
     */
    public function issueBook($studentId = null, $teacherId = null, $returnDate)
    {
        if (!$this->isAvailable() || (!$studentId && !$teacherId)) {
            return false;
        }

        // Decrease available quantity
        $this->available_quantity -= 1;
        $this->save();

        // Create book issue record
        return BookIssue::create([
            'book_id' => $this->id,
            'student_id' => $studentId,
            'teacher_id' => $teacherId,
            'issue_date' => now(),
            'return_date' => $returnDate,
            'status' => BookIssue::STATUS_ISSUED,
        ]);
    }

    /**
     * Get the current active issues for this book.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function activeIssues()
    {
        return $this->bookIssues()
            ->whereIn('status', [BookIssue::STATUS_ISSUED, BookIssue::STATUS_OVERDUE])
            ->get();
    }

    /**
     * Get cover image URL.
     *
     * @return string
     */
    public function getCoverImageUrl()
    {
        if ($this->cover_image) {
            return asset('storage/' . $this->cover_image);
        }

        return asset('images/book-placeholder.png');
    }

    /**
     * Get short description (truncated).
     *
     * @param int $length
     * @return string
     */
    public function getShortDescription($length = 100)
    {
        return Str::limit($this->description, $length);
    }

    /**
     * Update book quantities.
     *
     * @param int $newTotal
     * @return bool
     */
    public function updateQuantities($newTotal)
    {
        $currentIssues = $this->bookIssues()
            ->whereIn('status', [BookIssue::STATUS_ISSUED, BookIssue::STATUS_OVERDUE])
            ->count();

        // Cannot reduce total below current issues
        if ($newTotal < $currentIssues) {
            return false;
        }

        $this->total_quantity = $newTotal;
        $this->available_quantity = $newTotal - $currentIssues;

        return $this->save();
    }
}
