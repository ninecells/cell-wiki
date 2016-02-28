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
        $slug = $page->slug;

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 redirect 한다.
            return redirect("/wiki/$slug", 301);
        }

        $this->setMetaTas($page);

        return view('ncells::wiki.pages.wiki_page', ['page' => $page]);
    }

    public function GET_rev_page($key, $rev)
    {
        $page = $this->getPage($key);
        $slug = $page->slug;

        if (!$slug) {
            // 존재하지 않는 문서면 revision 을 보여줄 수 없으므로 생성 권유 페이지로 이동 시킨다.
            // 나중에 생성될 수 있는 문서이므로 일시적이동(302)
            return redirect("/wiki/$key", 302);
        }

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            // url 에 title 보다는 slug 를 권장하므로 영구적이동(301)
            return redirect("/wiki/$slug/$rev", 301);
        }

        $page = WikiHistory::where('wiki_page_id', $page->id)
            ->where('rev', $rev)
            ->first();

        if (!$page) {
            // 존재하지 않는 revision 인 경우 문서로 일시적이동(302)
            return redirect("/wiki/$slug", 302);
        }

        $this->setMetaTas($page);

        return view('ncells::wiki.pages.wiki_rev_page', ['page' => $page, 'rev' => $rev]);
    }

    public function GET_page_form($key)
    {
        $this->authorize('wiki-write');

        $page = $this->getPage($key);
        $slug = $page->slug;

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            // url 에 title 보다는 slug 를 권장하므로 영구적이동(301)
            return redirect("/wiki/$slug/edit", 301);
        }

        $this->setMetaTas($page);

        return view('ncells::wiki.pages.wiki_page_form', ['page' => $page]);
    }

    public function PUT_page_form(Request $request)
    {
        $this->authorize('wiki-write');
        $title = $request->input('title');
        $content = $request->input('content');
        $slug = self::slug($title);

        $page = $this->getPage($title);
        $page->rev = $page->rev + 1;
        $page->title = $title;
        $page->slug = $slug;
        $page->content = $content;
        $page->writer_id = Auth::user()->id;
        $page->save();

        $history = new WikiHistory();
        $history->wiki_page_id = $page->id;
        $history->rev = $page->rev;
        $history->title = $page->title;
        $history->slug = $page->slug;
        $history->content = $page->content;
        $history->writer_id = $page->writer_id;
        $history->created_at = $page->updated_at;
        $history->updated_at = $page->updated_at;
        $history->save();

        return redirect("/wiki/$slug");
    }

    public function GET_page_history($key)
    {
        $page = $this->getPage($key);
        $slug = $page->slug;

        if (!$slug) {
            return redirect("/wiki/$key", 302);
        }

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            // url 에 title 보다는 slug 를 권장하므로 영구적이동(301)
            return redirect("/wiki/$slug/history", 301);
        }

        $histories = WikiHistory::where('wiki_page_id', $page->id)
            ->with('writer')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        $this->setMetaTas($page);

        return view('ncells::wiki.pages.wiki_page_history', ['page' => $page, 'histories' => $histories]);
    }

    public function GET_page_compare($key, $left, $right)
    {
        $page = $this->getPage($key);
        $slug = $page->slug;

        if (!$slug) {
            // 존재하지 않는 문서이므로 생성 권장
            return redirect("/wiki/$key", 302);
        }

        if ($slug && $slug != $key) {
            // 이미 존재하는 문서인데 slug가 아니라 title로 들어왔다면 slug로 바꿔서 redirect 한다.
            // url 에 title 보다는 slug 를 권장하므로 영구적이동(301)
            return redirect("/wiki/$slug/compare/$left/$right", 301);
        }

        $l_page = WikiHistory::where('wiki_page_id', $page->id)->where('rev', $left)->first();
        $r_page = WikiHistory::where('wiki_page_id', $page->id)->where('rev', $right)->first();

        if (!$l_page || !$r_page) {
            // l 과 r 중 하나가 revision 이 없으므로 문서로 이동
            return redirect("/wiki/$slug", 302);
        }

        $this->setMetaTas($page);

        include "filediff.php";
        $opcodes = \FineDiff::getDiffOpcodes($l_page->content, $r_page->content, \FineDiff::characterDelimiters);
        $rendered_diff = \FineDiff::renderDiffToHTMLFromOpcodes($l_page->content, $opcodes);
        $rendered_diff = str_replace('\r\n', '\n', $rendered_diff);
        $rendered_diff = str_replace('\r', '\n', $rendered_diff);
        $rendered_diff = str_replace('\n', '&nbsp;<br/>', $rendered_diff);
        return view('ncells::wiki.pages.wiki_compare', ['page' => $page, 'rendered_diff' => $rendered_diff]);
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
                $page->rev = 0;
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

    private function setMetaTas($page)
    {
        // 메타 지정
        config(['title' => $page->title]);
        config(['og:title' => $page->title]);

        // 문서가 만들어진 경우에만 입력할 수 있는 meta 생성
        if ($page->slug) {
            $desc = strip_tags($page->md_content);
            $desc = str_replace("\r\n", "\n", $desc);
            $desc = str_replace("\r", " ", $desc);
            $desc = str_replace("\n", " ", $desc);
            $desc = $this->limit_words($desc, 30);

            $keys = $this->limit_words($page->title, 20);
            $keys = explode(' ', $keys);
            $keys = implode(',', $keys);

            config(['author' => $page->writer->name]);
            config(['description' => $desc]);
            config(['keywords' => $keys]);
            config(['og:description' => $desc]);
        }
    }

    private function limit_words($words, $limit, $append = ' &hellip;')
    {
        // Add 1 to the specified limit becuase arrays start at 0
        $limit = $limit + 1;
        // Store each individual word as an array element
        // Up to the limit
        $words = explode(' ', $words, $limit);
        // Shorten the array by 1 because that final element will be the sum of all the words after the limit
        array_pop($words);
        // Implode the array for output, and append an ellipse
        $words = implode(' ', $words) . $append;
        // Return the result
        return $words;
    }
}
