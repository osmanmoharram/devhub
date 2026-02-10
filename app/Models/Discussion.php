<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property int $category_id
 * @property int $user_id
 * @property bool $is_pinned
 * @property bool $is_locked
 * @property int $views_count
 * @property int $replies_count
 * @property \Carbon\Carbon|null $last_reply_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Category $category
 * @property-read User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Reply> $replies
 */
class Discussion extends Model
{
    /**
     * @use HasFactory<\Database\Factories\DiscussionFactory>
     */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_pinned' => 'boolean',
            'is_locked' => 'boolean',
            'views_count' => 'integer',
            'replies_count' => 'integer',
            'last_reply_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Category, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<Reply, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * @param  Builder<Discussion>  $query
     * @return Builder<Discussion>
     */
    #[Scope]
    protected function pinned(Builder $query): Builder
    {
        return $query->where('is_pinned', true);
    }

    /**
     * @param  Builder<Discussion>  $query
     * @return Builder<Discussion>
     */
    #[Scope]
    protected function popular(Builder $query): Builder
    {
        return $query->orderBy('replies_count', 'desc');
    }

    /**
     * @param  Builder<Discussion>  $query
     * @return Builder<Discussion>
     */
    #[Scope]
    protected function recent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * @param  Builder<Discussion>  $query
     * @return Builder<Discussion>
     */
    #[Scope]
    protected function forCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }
}
