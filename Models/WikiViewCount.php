<?php

namespace NineCells\Wiki\Models;

use Illuminate\Database\Eloquent\Model;

class WikiViewCount extends Model
{
    protected $fillable = [
        'wiki_page_id', 'ip', 'user_id',
    ];
}
