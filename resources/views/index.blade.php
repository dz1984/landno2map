@extends('layouts.default')


@section('content')
<div class="panel panel-primary">
    <div class="panel-heading">
        <h3 class="panel-title">上傳</h3>
    </div>
    <div class="panel-body">
      <form id="land_csv_upload_form" class="form-horizontal" method="post" action="/upload">
          <fieldset>
              <div class="form-group">
                  <label for="inputFile" class="col-lg-2 control-label">請選擇檔案</label>
                  <div class="col-lg-10">
                      <input type="text" readonly="" class="form-control floating-label" placeholder="">
                      <input type="file" id="inputFile" multiple="" name="land_csv_file">
                  </div>
              </div>
              <div class="form-group">
                  <div class="col-lg-10 col-lg-offset-2">                
                      <button type="submit" class="btn btn-primary">送出</button>
                  </div>
              </div>        
          </fieldset>
      </form>
    </div>
</div>

<div id="map-canvas"></div>
@stop

@section('script')
<script src="js/libs/jquery.form.min.js"></script>
<script src="js/app.js"></script>
<script>
  $.material.init();
</script>

<script type="text/template" id="item-tpl">
    <tr><td><%=land.k%></td><td><%=land.v%></td></tr>
</script>

<script type="text/template" id="content-tpl">

<div class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    X
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                         <table class="table table-striped">
                         <%=_.reduce(land_info,function(content, land){
                           var content_compiled = _.template($('#item-tpl').html());
                           var land_content = content_compiled({land:land});
                           return content+land_content;
                         },"")%>
                         </table> 
                    </div><!-- end col -->
                </div><!-- end row -->
            </div><!-- end modal-body -->
        </div><!-- end modal-content -->
    </div><!-- end modal-dialog -->
</div><!-- end modal -->
</script>
@stop