<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \App\Models\Categories create(array $attributes)
 *
 * @mixin IdeHelperCategories
 * @mixin Builder
 */
class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['category_name', 'status', 'deleted_by'];

    protected $appends = ['deleted_by_username'];

    protected $casts = [
        'category_name' => 'string',
        'status' => 'integer',
        'deleted_by' => 'integer',
    ];

    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getUpdatedAtFormattedAttribute(): string
    {
        return $this->updated_at->format('d/m/Y H:i');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function getDeletedByUsernameAttribute(): ?string
    {
        return $this->deletedBy?->username;
    }
}
