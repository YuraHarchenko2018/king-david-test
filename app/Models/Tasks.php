<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'status_id', 'board_id', 'image_link'
    ];

    public function boards() {
        return $this->belongsTo('App\Models\Boards', 'board_id', 'id');
    }
}
