<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'user_progress';

    public $timestamps = false;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'video_id',
        'progress_percent',
        'last_watched_at',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'progress_percent' => 'integer',
        'last_watched_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
