<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWikiTables extends Migration
{
    public function up()
    {
        Schema::create('wiki_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rev')->index();
            $table->string('title')->index();
            $table->string('slug')->index();
            $table->longText('content');
            $table->integer('writer_id')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('wiki_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wiki_page_id')->index();
            $table->integer('rev')->index();
            $table->string('title')->index();
            $table->string('slug')->index();
            $table->longText('content');
            $table->integer('writer_id')->index();
            $table->timestamps();
        });

        Schema::create('wiki_votes', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('votable');
            $table->integer('grade');
            $table->integer('voter_id')->index();
            $table->timestamps();
            $table->unique(['votable_id', 'votable_type', 'voter_id']);
        });

        Schema::create('wiki_view_counts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wiki_page_id')->index();
            $table->string('ip')->index();
            $table->integer('user_id')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('wiki_pages');
        Schema::drop('wiki_histories');
        Schema::drop('wiki_votes');
        Schema::drop('wiki_view_counts');
    }
}
