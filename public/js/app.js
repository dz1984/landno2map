(function(){
  var jq_info_msg = $('#info_msg');
  var jq_download_gejson = $('#download_geojson');

  // initial 
  jq_info_msg.hide();
  jq_download_gejson.hide();

  $('#land_csv_upload_form').submit(function(e) {
    e.preventDefault();

    $(this).ajaxSubmit(function(data) {

      // TODO : check the response was correct.
      //
      var status = data.status;

      if ('fail' == status) {
        jq_info_msg.find('p').text(data.msg);
        jq_info_msg.fadeIn();

        setTimeout(function(){
          jq_info_msg.fadeOut();
        }, 3000);
        return true;
      }

      // TODO : dispaly the export data link
      //

      var land_id = data.land_id;
      var download_link = '/api/land/download/' + land_id;

      jq_download_gejson.find('a').attr('href',download_link);
      jq_download_gejson.fadeIn();

      // render the map
      Land2Map.render(data);
    });
  });
})();