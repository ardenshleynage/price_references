<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Branch where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \App\Models\Branch create(array $attributes)
 *
 * @mixin IdeHelperBranch
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Branches extends Model
{
    use HasFactory;

    protected $table = 'branches';

    protected $fillable = ['branche_name', 'status'];

    protected $casts = [
        'branche_name' => 'string',
        'status' => 'integer',
    ];
}
