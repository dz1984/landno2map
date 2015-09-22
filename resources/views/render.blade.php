@extends('layouts.default')


@section('content')
<a href="/history" class="btn btn-flat btn-info">返回歷史清單</a>
<div id="map-canvas"></div>
@stop

@section('script')
<script src="/js/Land2Map.js"></script>
<script>
    $(function() {
        var id = {{ $land_id }};
        $.getJSON('/api/land/render/' + id, function(data) {
            Land2Map.render(data);
        });
    });
</script>
@stop

@include('includes.map')