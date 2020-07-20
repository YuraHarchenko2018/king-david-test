<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskStatuses extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'board_id'
    ];

    public $timestamps = false;
}
