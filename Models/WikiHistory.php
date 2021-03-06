<?php

namespace NineCells\Wiki\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WikiHistory extends Model
{
    protected $fillable = [
        'wiki_page_id', 'title', 'slug', 'content', 'writer_id', 'created_at', 'updated_at'
    ];

    public function getMdContentAttribute()
    {
        $content = $this->attributes['content'];
        $parsedown = new MyParsedown();
        return clean($parsedown->text($content));
    }

    public function writer()
    {
        return $this->hasOne('App\User', 'id', 'writer_id');
    }
}
