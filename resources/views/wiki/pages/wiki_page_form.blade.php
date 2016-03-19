@extends('ncells::app')

@section('content')

@include('ncells::wiki.parts.top_menu')
@include('ncells::wiki.parts.top_tab', ['type' => 'edit'])

<br/>

<form method="post" action="/wiki/update">
    {{ csrf_field() }}
    {{ method_field('PUT') }}
    <div class="form-group">
        <label for="title">제목</label>
        <input type="text" class="form-control" id="title" name="title" placeholder="제목" value="{{ $page->title }}">
    </div>
    <div class="form-group">
        <label for="content">내용</label>
        <textarea class="form-control" id="content" name="content" placeholder="내용"
                  rows="20">{{ $page->content }}</textarea>
    </div>
    <button type="submit" class="btn btn-default">저장</button>
</form>

@endsection
