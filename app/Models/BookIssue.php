<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Book;

class BookIssue extends Model
{
    use SoftDeletes;

    /**
     * Book issue status constants
     */
    const STATUS_ISSUED = 'issued';
    const STATUS_RETURNED = 'returned';
    const STATUS_OVERDUE = 'overdue';
    const STATUS_LOST = 'lost';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'book_id', 'student_id', 'teacher_id', 'issue_date',
        'return_date', 'actual_return_date', 'status', 'fine_amount', 'remarks'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fine_amount' => 'float',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'issue_date',
        'return_date',
        'actual_return_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Check for overdue books automatically
        static::retrieved(function ($bookIssue) {
            if ($bookIssue->status === self::STATUS_ISSUED &&
                $bookIssue->return_date < Carbon::today()) {
                $bookIssue->status = self::STATUS_OVERDUE;
                $bookIssue->save();
            }
        });
    }

    /**
     * Get the book associated with the issue.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the student who borrowed the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the teacher who borrowed the book.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Scope a query to only include overdue books.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue(Builder $query)
    {
        return $query->where('status', self::STATUS_OVERDUE)
            ->orWhere(function ($q) {
                $q->where('status', self::STATUS_ISSUED)
                  ->where('return_date', '<', Carbon::today());
            });
    }

    /**
     * Scope a query to only include currently issued books.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeIssued(Builder $query)
    {
        return $query->whereIn('status', [self::STATUS_ISSUED, self::STATUS_OVERDUE]);
    }

    /**
     * Calculate the fine amount for overdue books.
     *
     * @param float $ratePerDay Fine rate per day
     * @return float
     */
    public function calculateFine($ratePerDay = 1.00)
    {
        if ($this->status === self::STATUS_RETURNED && $this->actual_return_date > $this->return_date) {
            // Calculate days overdue
            $daysOverdue = Carbon::parse($this->return_date)
                ->diffInDays(Carbon::parse($this->actual_return_date));

            return $daysOverdue * $ratePerDay;
        }

        if (in_array($this->status, [self::STATUS_OVERDUE, self::STATUS_ISSUED]) &&
            $this->return_date < Carbon::today()) {
            // Calculate days overdue to date
            $daysOverdue = Carbon::parse($this->return_date)
                ->diffInDays(Carbon::today());

            return $daysOverdue * $ratePerDay;
        }

        return 0;
    }

    /**
     * Mark a book as returned.
     *
     * @param string|null $remarks
     * @return bool
     */
    public function markAsReturned($remarks = null)
    {
        $this->status = self::STATUS_RETURNED;
        $this->actual_return_date = Carbon::now();
        $this->fine_amount = $this->calculateFine();

        if ($remarks) {
            $this->remarks = $remarks;
        }

        // Update book quantity
        if ($this->book) {
            $this->book->available_quantity += 1;
            $this->book->save();
        }

        return $this->save();
    }

    /**
     * Mark a book as lost.
     *
     * @param string|null $remarks
     * @return bool
     */
    public function markAsLost($remarks = null)
    {
        $this->status = self::STATUS_LOST;

        if ($remarks) {
            $this->remarks = $remarks;
        }

        return $this->save();
    }

    /**
     * Check if the book issue is overdue.
     *
     * @return bool
     */
    public function isOverdue()
    {
        return $this->status === self::STATUS_OVERDUE ||
            ($this->status === self::STATUS_ISSUED && $this->return_date < Carbon::today());
    }

    /**
     * Get days remaining until return date.
     *
     * @return int
     */
    public function daysRemaining()
    {
        if ($this->status !== self::STATUS_ISSUED) {
            return 0;
        }

        $today = Carbon::today();
        $returnDate = Carbon::parse($this->return_date);

        if ($returnDate < $today) {
            return 0;
        }

        return $today->diffInDays($returnDate);
    }

    /**
     * Get days overdue.
     *
     * @return int
     */
    public function daysOverdue()
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        $today = Carbon::today();
        $returnDate = Carbon::parse($this->return_date);

        return $returnDate->diffInDays($today);
    }
}
