<?php

namespace NineCells\Wiki\Http\Controllers;

use Illuminate\Http\Request;

class WikiController extends Controller
{
    public function GET_page($page_key = null)
    {
        echo "1";
    }

    public function GET_page_form($page_key = null)
    {
        return view('ncells::wiki.pages.wiki_page');
    }
}
