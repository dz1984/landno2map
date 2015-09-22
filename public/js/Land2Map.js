(function(root, factory) {
    "use strict";

    if (typeof define === "function" && define.amd) {
        define(["jquery"], factory);
    } else if (typeof exports === "object") {
        module.exports = factory(require("jquery"));
    } else {
        root.Land2Map = factory(root.jQuery);
    }

})(this, function($) {
    "use strict";

    var exports = {
        map_id: 'map-canvas',
        map_option: {
            zoom: 15,
            scrollwheel: false
        }
    };

    exports.render = function(data) {
        var map_element = document.getElementById(exports.map_id);
        var map_object = new google.maps.Map(map_element, exports.map_option);
        var features = data.geo_json.features;

        map_object.data.addListener("click", function(event) {
            var feature = event.feature;

            // show the information. 
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

        // locate the map center
        $.each(features, function(index, feature){
            map_object.data.addGeoJson(feature);

            var lat = feature.properties.ycenter;
            var lng = feature.properties.xcenter;

            if (lat >0 && lng > 0) {
              center.lat = lat;
              center.lng = lng;
            }

        });

        map_object.setCenter(new google.maps.LatLng(center.lat, center.lng));
    };

    /**
     * the library version.
     */
    exports.version = function() {
        return 'v0.0.1';
    };

    /**
     * initial this lib.
     */
    exports.init = function(_$) {
        return init(_$ || $);
    };

    return exports;
});