<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $fillable = ['name', 'description', 'is_complete'];

    public function user()
    {
        $this->belongsTo(User::class, 'user_id', 'id');
    }
}
