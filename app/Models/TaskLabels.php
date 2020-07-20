<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLabels extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label_id', 'task_id'
    ];

    public $timestamps = false;
}
