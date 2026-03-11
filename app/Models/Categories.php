<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Categories where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \App\Models\Categories create(array $attributes)
 *
 * @mixin IdeHelperCategories
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Categories extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['category_name', 'status'];

    protected $casts = [
        'category_name' => 'string',
        'status' => 'integer',
    ];

    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getUpdatedAtFormattedAttribute(): string
    {
        return $this->updated_at->format('d/m/Y H:i');
    }
}
