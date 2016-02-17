<?php
use NineCells\Wiki\Models\WikiHistory;
$hs = WikiHistory::orderBy('id', 'desc')->paginate(10);
?>
<table class="table table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>제목</th>
        <th>변경</th>
        <th>변경한 사람</th>
    </tr>
    </thead>
    <tbody>
    @foreach ( $hs as $h )
    <tr>
        <td>{{ $h->rev }}</td>
        <td><a href="/wiki/{{ $h->slug }}/{{ $h->rev }}">{{ $h->title }}</a></td>
        <td>{{ $h->created_at->diffForHumans() }}</td>
        <td><a href="/members/{{ $h->writer_id }}">{{ $h->writer->name }}</a></td>
    </tr>
    @endforeach
    </tbody>
</table>
{!! $hs->links() !!}

