<?php

namespace NineCells\Wiki\Models;

use Illuminate\Database\Eloquent\Model;

class Wiki extends Model
{
    protected $fillable = [
        'votable_id', 'votable_type', 'grade', 'voter_id',
    ];

    public function voter()
    {
        return $this->hasOne('App\User', 'id', 'voter_id');
    }

    public function votable()
    {
        return $this->morphTo();
    }
}
