@extends('app')

@section('head')
<style>
h1, h2, h3, h4 {
    margin-top: 30px;
}
</style>
@endsection

@section('content')

@include('ncells::wiki.parts.top_menu')
@include('ncells::wiki.parts.top_tab', ['type' => 'view'])

<h1>{{ $page->title }}</h1>

@if( $page->slug )
    <p>{!! $page->md_content !!}</p>
@else
    <p>
        아직 존재하지 않는 문서입니다.
        @if(Auth::check())
        "<a href="/wiki/{{ $page->title }}/edit">{{ $page->title }}</a>" 문서를 만들어주세요.
        @endif
    </p>
@endif

@endsection
