<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Products where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \App\Models\Products create(array $attributes)
 *
 * @mixin IdeHelperProducts
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_name',
        'post_scriptum',
        'single_price',
        'detailed_price',
        'branch_id',
        'category_id',
        'status',
    ];

    protected $casts = [
        'product_name' => 'string',
        'post_scriptum' => 'string',
        'single_price' => 'decimal:2',
        'detailed_price' => 'string',
        'branch_id' => 'integer',
        'category_id' => 'integer',
        'status' => 'integer',
    ];

    /**
     * @return BelongsTo<Branches,Products>
     */
    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branch_id');
    }

    /**
     * @return BelongsTo<Categories,Products>
     */
    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }

    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    public function getUpdatedAtFormattedAttribute(): string
    {
        return $this->updated_at->format('d/m/Y H:i');
    }

    public function getBranchNameAttribute(): string
    {
        return $this->branch ? $this->branch->branche_name : 'Aucun';
    }

    public function getCategoryNameAttribute(): string
    {
        return $this->category ? $this->category->category_name : 'Aucun';
    }
}
