<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CoursePreorder extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'user_id',
        'name',
        'contact',
        'ip_address',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
