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

map.on('singleclick', function (event) {
    addMarker(event.coordinate);
});

map.on('click', function (e) {
    map.forEachFeatureAtPixel(e.pixel, function (feature, layer) {
        popup.show(e.coordinate,
            '<strong>' + feature.get('name') + '</strong>'
        );
    })
});

function updateInputs(x, y) {
    document.querySelector('#backendbundle_business_mallMapX').value = x;
    document.querySelector('#backendbundle_business_mallMapY').value = y;
}

function addMarker(coordinate) {
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
            name: document.querySelector('#backendbundle_business_name').value,
            description: document.querySelector('#backendbundle_business_description').value,
            population: 4000,
            rainfall: 500
        });

        // update inputs
        updateInputs(coordinate[1], coordinate[0]);

        var modifyInteraction = makeMovable(iconFeature);
        map.addInteraction(modifyInteraction);  // make the marker movable, update the coordenate fields

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
                    text: 'Wow such label',
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

        // self.dynamicPinLayer = undefined;
    }
}

function makeMovable(feature) {
    var modify = new ol.interaction.Modify({
        features: new ol.Collection([feature])
    });

    feature.on('change', function () {
        console.log('Feature Moved To: ' + this.getGeometry().getCoordinates());

        updateInputs(this.getGeometry().getCoordinates()[1], this.getGeometry().getCoordinates()[0]);
    }, feature);

    return modify;
}

function placeMarker() {
    var x = document.querySelector('#backendbundle_business_mallMapX').value;
    var y = document.querySelector('#backendbundle_business_mallMapY').value;

    if ('' !== x && '' !== y) {
        addMarker([y, x]);
    }
}