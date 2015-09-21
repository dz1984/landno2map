@extends('layouts.default')


@section('content')

<h1>History</h1>

<h3> TODO: show the summary list.</h3>
<div id="render-list">

@foreach ($summaries as $summary)
    <p>{{ $summary->id }}</p>
    <p>增加欄位：{{ implode(',', unserialize($summary->fields)) }}</p>
    <p>建立時間：{{$summary->created_at}}</p>
    <a href="/map/">檢視地圖</a>
@endforeach
</div>
@stop

@section('script')

@stop
