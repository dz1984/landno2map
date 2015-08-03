@extends('layouts.default')


@section('content')

<h1>History</h1>

<h3> TODO: show the summary list.</h3>
@stop

@section('script')
<script>
    $.getJson('api/land/list',function(data) {
        console.log(data);
    });
</script>
@stop
