<?php

namespace NineCells\Wiki\Http\Controllers;

use Illuminate\Http\Request;

class WikiController extends Controller
{
    public function GET_page($page_key = null)
    {
        return view('ncells::wiki.pages.wiki_page');
    }

    public function GET_page_form($page_key = null)
    {
        return view('ncells::wiki.pages.wiki_page_form');
    }
}
