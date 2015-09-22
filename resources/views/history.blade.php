@extends('layouts.default')


@section('content')
<h1>歷史清單</h1>

<table class="table table-striped table-hover ">
    <thead>
        <tr>
            <th>#</th>
            <th>增加欄位</th>
            <th>建立時間</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
@foreach ($summaries as $summary)
    <tr>
    <td><span>{{ $summary->id }}</span></td>
    <td>{{ implode(',', unserialize($summary->fields)) }}</td>
    <td>{{ $summary->created_at }}</td>
    <td>
        <a href="/render/{{ $summary->id }}" alt="檢視地圖" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="檢視地圖"><i class="mdi-maps-place"></i></a>
    </td>
    <td>
        <a href="/api/land/download/{{ $summary->id }}"  data-toggle="tooltip" data-placement="bottom" title="" data-original-title="匯出GeoJson"><i class="mdi-file-file-download"></i></a>
    </td>
    </tr>
@endforeach
    </tbody>
</table>
@stop

@section('script')
<script>
    $(function(){
        $.material.init();
        $('a[data-toggle="tooltip"]').tooltip();

    });
</script>
@stop
