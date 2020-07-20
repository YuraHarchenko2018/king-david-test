<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Boards extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'owner_id'
    ];

    public function tasks() {
        return $this->hasMany('App\Models\Tasks', 'board_id', 'id');
    }

}
