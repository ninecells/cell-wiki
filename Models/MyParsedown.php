<?php

namespace NineCells\Wiki\Models;

class MyParsedown extends \Parsedown
{
    function __construct()
    {
        $this->InlineTypes['['][]= 'WikiLink';

        $this->inlineMarkerList .= '[';
    }

    protected function inlineWikiLink($Excerpt)
    {
        if (preg_match('/^\[\[wiki:(.*?)\]\]/', $Excerpt['text'], $matches))
        {
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'div',
                    'text' => view('ncells::wiki.parts.wikitag_'.$matches[1]),
                ),
            );
        }

        if (preg_match('/^\[\[(.*?)\]\]/', $Excerpt['text'], $matches))
        {
            return array(
                'extent' => strlen($matches[0]),
                'element' => array(
                    'name' => 'a',
                    'text' => $matches[1],
                    'attributes' => array(
                        'href' => '/wiki/'.$matches[1],
                    ),
                ),
            );
        }
    }
}