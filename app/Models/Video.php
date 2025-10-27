<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Video extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'title',
        'short_description',
        'full_description',
        'video_url',
        'preview_image',
        'duration',
        'sort_order',
        'is_free',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'duration' => 'integer',
        'sort_order' => 'integer',
        'is_free' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function getVideoUrlAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        if ($this->isExternalFilePath($value)) {
            return $value;
        }

        return Storage::disk('public')->url($value);
    }

    public function getPreviewImageAttribute(?string $value): ?string
    {
        if (!$value) {
            return null;
        }

        if ($this->isExternalFilePath($value)) {
            return $value;
        }

        return Storage::disk('public')->url($value);
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    private function isExternalFilePath(string $path): bool
    {
        return Str::startsWith($path, ['http://', 'https://', '//', '/']);
    }
}
