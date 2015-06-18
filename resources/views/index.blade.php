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
@stop

@section('script')
<script src="js/libs/jquery.form.min.js"></script>
<script src="js/app.js"></script>
@stop