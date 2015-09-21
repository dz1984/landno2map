@extends('layouts.default')


@section('content')
<div id="map-canvas"></div>
@stop

@section('script')
<script src="/js/Land2Map.js"></script>
<script>

    $(function() {
        $.getJSON('/api/land/render/66', function(data) {
            Land2Map.render(data);
        });
    });
</script>
@stop
