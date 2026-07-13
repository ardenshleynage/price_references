<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Branch where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \App\Models\Branch create(array $attributes)
 *
 * @mixin IdeHelperBranch
 * @mixin Builder
 */
class Branches extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = ['branche_name', 'status', 'deleted_by'];

    protected $casts = [
        'branche_name' => 'string',
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
}
