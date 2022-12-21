// Initialize the platform object:
var platform = new H.service.Platform({
    apikey: "jUocnu7hdQ9_ZyYLLJn4XNSrxgI8HoCjEcZ2iPg2vRs"
});


// Obtain the default map types from the platform object
var maptypes = platform.createDefaultLayers();

// Instantiate (and display) a map object:
var map = new H.Map(
    document.getElementById("mapContainer"),
    maptypes.vector.normal.map,
    {
        zoom: 5,
        center: { lng: 107.600, lat: -6.919 },
        pixelRatio: window.devicePixelRatio || 1
    });

var ui = H.ui.UI.createDefault(map, maptypes);
ui.getControl('zoom').setAlignment(H.ui.LayoutAlignment.LEFT_TOP);
window.addEventListener('resize', () => map.getViewPort().resize());

var behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));


// point
var start, waypoints = [], finish, bURL = 'http://localhost/bht/api/public';
if (window.location.hostname !== 'localhost') {
    bURL = 'http://110.5.105.26/bht/api/public';
}
// Api Calls
$.get(bURL + "/api/track/map?sq=808080", function (data) {
    // console.log(data.data)
    $.each(data.data, function (k, v) {

        if (Math.floor(v.LATITUDE) !== 0) {
            if (k === 0) {
                start = v.LATITUDE + "," + v.LONGITUDE;
            } else if (k === data.count - 1) {
                end = v.LATITUDE + "," + v.LONGITUDE;
            } else {
                waypoints.push(v.LATITUDE + "," + v.LONGITUDE);
            }
        }
    });


    // start = '-6.2040409994965895,106.8274726766179';

    // collection of waypoints
    // waypoints = [
    //     '-6.215253610401715,106.83058582572363',
    //     '-6.2260093745872345,106.8331375872857',
    //     '-6.233213584044571,106.83155549511723'
    // ];
    console.log(start,waypoints,end)
    // var sss = new H.service.Url.MultiValueQueryParameter( waypoints );
    // console.log('ss',sss)

    // end point (destination)
    // end = '-6.228596813306077,106.81854151115066'

    var routingParameters = {
        'origin': start,
        'destination': end,
        'via': new H.service.Url.MultiValueQueryParameter(waypoints),
        'routingMode': 'fast',
        'transportMode': 'car',
        'return': 'polyline'
    };

    // Define a callback function to process the routing response:
    var onResult = function (result) {
        // ensure that at least one route was found
        // console.log('aa',result.routes,result.routes.length)
        if (result.routes.length) {
            result.routes[0].sections.forEach((section) => {
                // Create a linestring to use as a point source for the route line
                let linestring = H.geo.LineString.fromFlexiblePolyline(section.polyline);

                // Create a polyline to display the route:
                let routeLine = new H.map.Polyline(linestring, {
                    style: { strokeColor: 'blue', lineWidth: 5 }
                });
                // console.log('adada', section.departure, section.arrival)
                  console.log('====',section.departure.place.waypoint,section.arrival.place.waypoint)
                // Create a marker for the start point:
                let startMarker, endMarker, markervar;
                var icon, size;

                if (typeof section.departure.place.waypoint === 'undefined') {
                    // console.log('und1')

                    markervar = 'img/start.png';
                    size = { w: 56, h: 56 };
                    // console.log(startMarker)
                    // map.addObject(marker);

                    // startMarker = new H.map.Marker(section.departure.place.location);
                } else {
                    // startMarker = new H.map.Marker(section.departure.place.location);
                    markervar = 'img/marker.png';
                    size = { w: 50, h: 50 };
                }
                icon = new H.map.Icon(markervar, { size: size });
                startMarker = new H.map.Marker(section.departure.place.location, { icon: icon });

                if (typeof section.arrival.place.waypoint === 'undefined') {
                    markervar = 'img/finish.png';
                    size = { w: 56, h: 56 };
                } else {
                    markervar = 'img/marker.png';
                    size = { w: 50, h: 50 };
                }
                icon = new H.map.Icon(markervar, { size: size });
                endMarker = new H.map.Marker(section.arrival.place.location, { icon: icon });


                // console.log('und2')


                // endMarker = new H.map.Marker(section.arrival.place.location);
                //   }else{
                //     endMarker = new H.map.Marker(section.arrival.place.location);
                //   }

                // Add the route polyline and the two markers to the map:
                map.addObjects([routeLine, startMarker, endMarker]);

                // Set the map's viewport to make the whole route visible:
                map.getViewModel().setLookAtData({ bounds: routeLine.getBoundingBox() });
            });
        }
    };

    var router = platform.getRoutingService(null, 8);
    router.calculateRoute(routingParameters, onResult,
        function (error) {
            console.log(error.message);
        }
    );

});

