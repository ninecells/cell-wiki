@extends('ncells::app')

@section('content')
@include('ncells::wiki.parts.top_menu')
@include('ncells::wiki.parts.top_tab', ['type' => 'history'])
<h1>비교: {{ $page->title }}</h1>
<div class="panecontainer">
    <div id="htmldiff" class="pane" style="white-space:pre-wrap"><?php echo $rendered_diff; ?></div>
</div>
@endsection

@section('head')
<style type="text/css">
    ins {
        color: green;
        background: #dfd;
        text-decoration: none
    }

    del {
        color: red;
        background: #fdd;
        text-decoration: none
    }

    .panecontainer > p {
        margin: 0;
        border: 1px solid #bcd;
        border-bottom: none;
        padding: 1px 3px;
        background: #def;
        font: 14px sans-serif
    }

    .panecontainer > p + div {
        margin: 0;
        padding: 2px 0 2px 2px;
        border: 1px solid #bcd;
        border-top: none
    }

    .pane {
        margin: 0;
        padding: 0;
        border: 0;
        width: 100%;
        min-height: 20em;
        overflow: auto;
        font: 12px monospace
    }

    #htmldiff {
        color: gray
    }

    #htmldiff.onlyDeletions ins {
        display: none
    }

    #htmldiff.onlyInsertions del {
        display: none
    }
</style>
@endsection
