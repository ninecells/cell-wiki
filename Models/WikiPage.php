<?php

namespace NineCells\Wiki\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WikiPage extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'content', 'writer_id',
    ];

    public function getMdContentAttribute()
    {
        $content = $this->attributes['content'];
        $parsedown = new MyParsedown();
        return $parsedown->text($content);
    }

    public function votes()
    {
        return $this->morphMany(WikiVote::class, 'votable');
    }

    public function writer()
    {
        return $this->hasOne('App\User', 'id', 'writer_id');
    }

    public function viewCounts()
    {
        return $this->hasMany(WikiViewCount::class, 'wiki_page_id');
    }
}
