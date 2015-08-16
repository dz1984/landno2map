@extends('layouts.default')


@section('content')

<h1>History</h1>

<h3> TODO: show the summary list.</h3>
<div id="render-list"></div>
@stop

@section('script')
<script type="text/template" id="content-tpl">
</script>
<script>
    $.getJSON('api/land/list',function(data) {
        // TODO : render the lands records
        //
        console.log(data);
    });
</script>
@stop
