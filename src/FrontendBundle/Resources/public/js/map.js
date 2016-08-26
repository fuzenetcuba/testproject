var extent = [0, 0, 1506, 2037];
var projection = new ol.proj.Projection({
    code: 'xkcd',
    units: 'pixels',
    extent: extent
});

var map = new ol.Map({
    layers: [
        new ol.layer.Image({
            source: new ol.source.ImageStatic({
                attributions: [
                    new ol.Attribution({
                        html: '<strong style="font-size: 11px;">&copy; Fuzenet Marketing, 2016</strong>'
                    })
                ],
                url: '/bundles/backend/images/map.png',
                projection: projection,
                imageExtent: extent
            })
        })
    ],
    target: 'map',
    view: new ol.View({
        projection: projection,
        center: ol.extent.getCenter(extent),
        zoom: 1,
        minZoom: 1,
        maxZoom: 3,
    })
});

markers = [];

var popup = new ol.Overlay.Popup();
map.addOverlay(popup);

map.on('click', function (e) {
    map.forEachFeatureAtPixel(e.pixel, function (feature, layer) {
        popup.show(e.coordinate,
            '<strong>' + feature.get('name') + '</strong>'
        );
    })
});

function addMarker(coordinate, name) {
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
            rainfall: 500
        });

        var iconStyle = [
            new ol.style.Style({
                image: new ol.style.Icon(({
                    anchor: [0.5, 46],
                    anchorXUnits: 'fraction',
                    anchorYUnits: 'pixels',
                    size: [48, 48],
                    opacity: 1,
                    src: '/bundles/backend/images/pin.png'
                }))
            }),
            new ol.style.Style({
                text: new ol.style.Text({
                    text: name,
                    offsetY: -55,
                    fill: new ol.style.Fill({
                        color: '#333333'
                    })
                })
            })
        ];

        iconFeature.setStyle(iconStyle);

        var vectorSource = new ol.source.Vector({
            features: [iconFeature]
        });

        self.dynamicPinLayer = new ol.layer.Vector({
            source: vectorSource
        });

        map.addLayer(self.dynamicPinLayer);

        markers.push(self.dynamicPinLayer);
        self.dynamicPinLayer = undefined;
    }
}