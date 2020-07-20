<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Labels extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'user_id'
    ];

    public $timestamps = false;
}
