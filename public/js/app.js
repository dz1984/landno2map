(function(){
  $('#land_csv_upload_form').submit(function(e) {
    e.preventDefault();

    $(this).ajaxSubmit(function(data) {

      // TODO : rende the map
      // 
      var map_id = 'map-canvas';
      var map_options = {
        zoom: 15,
        scrollwheel: false
      };

      var map_element = document.getElementById(map_id);
      var map_object = new google.maps.Map(map_element, map_options);
      var features = data.geo_json.features;

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
      console.log(data);
    });
  });
})();