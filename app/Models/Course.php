<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'price',
        'is_free',
        'start_date',
        'thumbnail',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_free' => 'boolean',
        'start_date' => 'datetime',
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('sort_order');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    public function preorders(): HasMany
    {
        return $this->hasMany(CoursePreorder::class);
    }

    public function hasStarted(): bool
    {
        if ($this->start_date === null) {
            return true;
        }

        return $this->start_date->lte(now());
    }

    public function isUpcoming(): bool
    {
        return ! $this->hasStarted();
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail) {
            return null;
        }

        if (Str::startsWith($this->thumbnail, ['http://', 'https://'])) {
            return $this->thumbnail;
        }

        return Storage::disk('public')->url($this->thumbnail);
    }

    /**
     * Apply the provided filters to the query.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when(
            filled($filters['search'] ?? null),
            function (Builder $query, string $search) {
                $searchTerm = trim($search);

                if ($searchTerm === '') {
                    return;
                }

                $normalized = Str::lower($searchTerm);
                $likeExpression = "%{$normalized}%";

                $query->where(function (Builder $query) use ($likeExpression) {
                    $query
                        ->whereRaw('LOWER(title) LIKE ?', [$likeExpression])
                        ->orWhereRaw('LOWER(description) LIKE ?', [$likeExpression]);
                });
            }
        );

        $query->when(
            ($filters['type'] ?? null) === 'free',
            fn (Builder $query) => $query->where('is_free', true)
        );

        $query->when(
            ($filters['type'] ?? null) === 'paid',
            fn (Builder $query) => $query->where('is_free', false)
        );
    }
}
