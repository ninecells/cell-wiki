@extends('ncells::app')

@section('content')

@include('ncells::wiki.parts.top_menu')
@include('ncells::wiki.parts.top_tab', ['type' => 'history'])

<h1>역사: {{ $page->title }}</h1>
<p>
    <a id="btn-compare" href="#" class="btn btn-success" data-page-slug="{{ $page->slug }}">비교하기</a>
</p>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>L</th>
        <th>R</th>
        <th>#</th>
        <th>제목</th>
        <th>변경</th>
        <th>변경한 사람</th>
    </tr>
    </thead>
    <tbody>
    @foreach ( $histories as $history )
    <tr>
        <td><input name="left" type="radio" value="{{ $history->rev }}" /></td>
        <td><input name="right" type="radio" value="{{ $history->rev }}" /></td>
        <td>{{ $history->rev }}</td>
        <td><a href="/wiki/{{ $history->slug }}/{{ $history->rev }}">{{ $history->title }}</a></td>
        <td>{{ $history->created_at->diffForHumans() }}</td>
        <td><a href="/members/{{ $history->writer_id }}">{{ $history->writer->name }}</a></td>
    </tr>
    @endforeach
    </tbody>
</table>
@endsection


@section('script')
@parent
<script>
    $(function () {
        $('#btn-compare').click(function (e) {
            var left = $('input[name=left]:checked').val(),
                right = $('input[name=right]:checked').val(),
                url = '/wiki/' + $(this).data('page-slug') + '/compare/' + left + '/' + right;
            if(!left) {
                alert('L을 선택하세요');
                return false;
            }
            if(!right) {
                alert('R을 선택하세요');
                return false;
            }
            window.location.href = url;
            return false;
        });
    });
</script>
@endsection