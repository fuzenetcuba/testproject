var map_blank = $('#map_blank').prop('value');
var map_pin = $('#map_pin').prop('value');
var extent = [0, 0, 1506, 1100];
var projection = new ol.proj.Projection({
    code: 'xkcd',
    units: 'pixels',
    extent: extent
});

var url = Routing.getBaseUrl().replace('app_dev.php', '');

var raster = new ol.layer.Image({
    source: new ol.source.ImageStatic({
        attributions: [
            new ol.Attribution({
                html: '<strong style="font-size: 11px;">&copy; Fuzenet Marketing, 2016</strong>'
            })
        ],
        url: url + (url.endsWith('/') ? '' : '/') + 'bundles/backend/images/map.png',
        projection: projection,
        imageExtent: extent
    })
});

var map = new ol.Map({
    layers: [
        raster
    ],
    target: 'map',
    view: new ol.View({
        projection: projection,
        center: ol.extent.getCenter(extent),
        zoom: 2,
        minZoom: 1,
        maxZoom: 4,
    })
});

markers = [];

var popup = new ol.Overlay.Popup();
map.addOverlay(popup);

var source = new ol.source.Vector({
    wrapX: false
});

var vector = new ol.layer.Vector({
    source: source
});

map.addLayer(vector);

source.on('addfeature', function (e) {
    flash(e.feature);
});

map.on('click', function (e) {
    map.forEachFeatureAtPixel(e.pixel, function (feature, layer) {
        popup.show(e.coordinate,
            '<strong>' + feature.get('name') + '</strong>'
        );
    })
});

const ANIMATION_DURATION = 500;

function flash(feature) {

    if (!feature.get('animate')) {
        return ;
    }

    var start = new Date().getTime();
    var listenerKey;
    var flashGeom = feature.getGeometry().clone();

    var coordinates = flashGeom.getCoordinates();
    // x-1, y-0
    coordinates[0] = coordinates[0] - 40;
    flashGeom.setCoordinates(coordinates);

    function animate(event) {
        var vectorContext = event.vectorContext;
        var frameState = event.frameState;
        var elapsed = frameState.time - start;
        var elapsedRatio = elapsed / ANIMATION_DURATION;
        // radius will be 5 at start and 40 at end.
        var radius = ol.easing.easeOut(elapsedRatio) * 40 + 5;
        var opacity = ol.easing.easeOut(1 - elapsedRatio);

        var style = new ol.style.Style({
            image: new ol.style.Circle({
                radius: radius,
                snapToPixel: false,
                stroke: new ol.style.Stroke({
                    color: 'rgba(253, 178, 0, ' + opacity + ')',
                    width: 3 + opacity
                })
            })
        });

        vectorContext.setStyle(style);
        vectorContext.drawGeometry(flashGeom);

        if (elapsed > ANIMATION_DURATION) {
            ol.Observable.unByKey(listenerKey);
            return;
        }

        // tell OL3 to continue postcompose animation
        map.render();
    }

    listenerKey = map.on('postcompose', animate);
}

function addMarker(coordinate, name, animate) {
    var self = this;

    var latLong = ol.proj.transform(coordinate, 'EPSG:3857', 'EPSG:4326');

    var x = latLong[1];
    var y = latLong[0];

    // console.log(x);
    // console.log(y);

    if (undefined === self.dynamicPinLayer) {
        // not moving
        self.iconGeometry = new ol.geom.Point(coordinate);
        var iconFeature = new ol.Feature({
            geometry: self.iconGeometry,
            name: name,
            population: 4000,
            rainfall: 500,
            animate: animate
        });

        var iconStyle = [
            new ol.style.Style({
                image: new ol.style.Icon(({
                    anchor: [0.5, 46],
                    anchorXUnits: 'fraction',
                    anchorYUnits: 'pixels',
                    size: [48, 48],
                    opacity: 1,
                    src: map_pin
                }))
            }),
            new ol.style.Style({
                text: new ol.style.Text({
                    font: '12px Open Sans Condensed,sans-serif',
                    textAlign: 'center',
                    fill: new ol.style.Fill({
                        color: [0, 0, 0, 1]
                    }),
                    stroke: new ol.style.Stroke({
                        color: [255, 255, 255, 0.5],
                        width: 4
                    }),
                    text: name,
                    offsetY: -55,
                    offsetX: -10,
                })
            })
        ];

        iconFeature.setStyle(iconStyle);

        source.addFeature(iconFeature);

        self.dynamicPinLayer = vector;

        markers.push(self.dynamicPinLayer);
        self.dynamicPinLayer = undefined;
    }
}
