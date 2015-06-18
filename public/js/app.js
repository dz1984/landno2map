(function(){
  $('#land_csv_upload_form').submit(function(e) {
    e.preventDefault();

    $(this).ajaxSubmit();
  });
})();