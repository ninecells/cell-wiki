<?php

namespace NineCells\Wiki\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use NineCells\Wiki\Models\WikiHistory;
use NineCells\Wiki\Models\WikiPage;

class WikiController extends Controller
{
    public function GET_page($key = 'Main')
    {
        $page = $this->getPage($key);
        if ($page->slug && $key != $page->slug) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 redirect 한다.
            $slug = $page->slug;
            return redirect("/wiki/$slug", 301);
        }
        return view('ncells::wiki.pages.wiki_page', ['page' => $page]);
    }

    public function GET_page_form($key)
    {
        $this->authorize('wiki-write');
        $page = $this->getPage($key);
        return view('ncells::wiki.pages.wiki_page_form', ['page' => $page]);
    }

    public function PUT_page_form(Request $request)
    {
        $this->authorize('wiki-write');
        $title = $request->input('title');
        $content = $request->input('content');
        $slug = self::slug($title);

        $page = $this->getPage($title);
        if ($page->slug) {
            // 이미 존재하던 문서면 history에 넣는다
            $history = new WikiHistory();
            $history->wiki_page_id = $page->id;
            $history->title = $page->title;
            $history->slug = $page->slug;
            $history->content = $page->content;
            $history->writer_id = $page->writer_id;
            $history->created_at = $page->created_at;
            $history->updated_at = $page->updated_at;
            $history->save();
        }
        $page->title = $title;
        $page->slug = $slug;
        $page->content = $content;
        $page->writer_id = Auth::user()->id;
        $page->save();

        return redirect("/wiki/$slug");
    }

    private function getPage($key)
    {
        $key = trim($key);
        $key = preg_replace('/\s+/', ' ', $key);
        $page = WikiPage::where('slug', self::slug($key))->first();
        if (!$page) {
            $page = WikiPage::where('title', $key)->first();
            if (!$page) {
                $page = new WikiPage();
                $page->title = $key;
                $page->slug = null; // view 에서 slug가 없으면 title을 사용하므로 null 처리
                $page->content = '';
            }
        }
        return $page;
    }

    public static function slug($title, $separator = '-')
    {
        $flip = $separator == '-' ? '_' : '-';
        $title = preg_replace('![' . preg_quote($flip) . ']+!u', $separator, $title);
        $title = preg_replace('![^' . preg_quote($separator) . '\pL\pN\s]+!u', '', mb_strtolower($title));
        $title = preg_replace('![' . preg_quote($separator) . '\s]+!u', $separator, $title);
        return trim($title, $separator);
    }
}
