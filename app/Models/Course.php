<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'thumbnail',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'is_free' => 'boolean',
    ];

    public function videos(): HasMany
    {
        return $this->hasMany(Video::class)->orderBy('sort_order');
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Apply the provided filters to the query.
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        $query->when(
            filled($filters['search'] ?? null),
            fn (Builder $query, string $search) => $query->where(function (Builder $query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
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
