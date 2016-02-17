<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"{!! $type === 'view' ? ' class="active"' : '' !!}>
        <a href="/wiki/{{ $page->slug or $page->title }}" aria-controls="view" role="tab" data-toggle="tab">보기</a>
    </li>
    @can('wiki-write')
    <li role="presentation"{!! $type === 'edit' ? ' class="active"' : '' !!}>
        <a href="/wiki/{{ $page->slug or $page->title }}/edit" aria-controls="edit" role="tab" data-toggle="tab">편집</a>
    </li>
    @endcan
    <li role="presentation"{!! $type === 'history' ? ' class="active"' : '' !!}>
        <a href="/wiki/{{ $page->slug or $page->title }}/history" aria-controls="history" role="tab" data-toggle="tab">역사</a>
    </li>
</ul>
