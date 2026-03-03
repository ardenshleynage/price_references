<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\EndUser where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \App\Models\EndUser create(array $attributes)
 *
 * @mixin IdeHelperEndUser
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class EndUser extends Model
{
    use HasFactory;

    protected $table = 'end_user';

    protected $fillable = [
        'username',
        'password',
        'role',
        'last_time_connect',
        'status',
        'theme',
    ];

    protected $casts = [
        'username' => 'string',
        'password' => 'string',
        'role' => 'integer',
        'last_time_connect' => 'string',
        'status' => 'integer',
        'theme' => 'string',
    ];
}
