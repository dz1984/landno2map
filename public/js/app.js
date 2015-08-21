(function(){
  $('#land_csv_upload_form').submit(function(e) {
    e.preventDefault();

    $(this).ajaxSubmit(function(data) {

      // TODO : render the map
      // 
      var map_id = 'map-canvas';
      var map_options = {
        zoom: 15,
        scrollwheel: false
      };

      var map_element = document.getElementById(map_id);
      var map_object = new google.maps.Map(map_element, map_options);
      var features = data.geo_json.features;

      map_object.data.addListener("click", function(event) {
        var feature = event.feature;

        // TODO : show the information.
        // 
        var land_info = [];

        $.each(data.field_names, function(index, field) {
          land_info.push({k: field, v: feature.getProperty(field)});
        });

        var content_compiled = _.template($('#content-tpl').text());
        var content = content_compiled({land_info:land_info});
        $(content).modal({backdrop: false});
      });

      var center = {
        lat: -1,
        lng: -1
      };

      features.forEach(function(feature){
        map_object.data.addGeoJson(feature);

        var lat = feature.properties.ycenter;
        var lng = feature.properties.xcenter;

        if (lat >0 && lng > 0) {
          center.lat = lat;
          center.lng = lng;
        }

      });

      map_object.setCenter(new google.maps.LatLng(center.lat, center.lng));
    });
  });
})();