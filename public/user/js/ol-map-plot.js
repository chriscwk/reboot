var map;

function plot_map(mapId, lat, long)
{
    map = new ol.Map({
        target: mapId,
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            })
        ],
        view: new ol.View({
            center: ol.proj.fromLonLat([long, lat]),
            zoom: 16
        })
    });

    var markerStyle = [
        new ol.style.Style({
            image: new ol.style.Icon(({
                scale: 0.7,
                rotateWithView: false,
                anchor: [0.5, 1],
                anchorXUnits: 'fraction',
                anchorYUnits: 'fraction',
                opacity: 1,
                src: '/image/map-marker-icon.png'
            })),
            zIndex: 5
        }),
        new ol.style.Style({
            image: new ol.style.Circle({
                radius: 5,
                fill: new ol.style.Fill({
                    color: 'rgba(231,76,60,1)'
                }),
                stroke: new ol.style.Stroke({
                    color: 'rgba(231,76,60,1)'
                })
            })
        })
    ];

    var marker = new ol.Feature({ geometry: new ol.geom.Point(ol.proj.fromLonLat([long, lat])) });
    marker.setStyle(markerStyle);
    var vectorSource = new ol.source.Vector({ features: [marker] });
    var layerFeatures = new ol.layer.Vector({ source: vectorSource });

    map.addLayer(layerFeatures);
}

function destroyMap()
{
    if (this.map != null)
    {
        this.map.setTarget(null);
        this.map = null;
    }
}