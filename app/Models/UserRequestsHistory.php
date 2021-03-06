<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRequestsHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'name',
        'symbol',
        'open',
        'high',
        'low',
        'close',
        'user_id'
    ];
}
