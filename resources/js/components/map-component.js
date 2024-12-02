import './../../css/ol.css'
import Map from 'ol/Map.js';
import View from 'ol/View.js';
import TileLayer from 'ol/layer/Tile.js';
import OSM from 'ol/source/OSM.js';
import {
    Style,
    Fill,
    Stroke,
    Circle,
    Text
} from 'ol/style.js';

export default function mapComponent() {
    return {
        legendOpened: false,
        map: {},
        features: [],
        init() {
            this.map = new Map({
                target: this.$refs.map,
                layers: [
                    new TileLayer({
                        source: new OSM(),
                        label: 'OpenStreetMap',
                    }),
                ],
                view: new View({
                    projection: 'EPSG:4326',
                    center: [0, 0],
                    zoom: 2,
                }),
            })
        },
        styleFunction(feature, resolution) {
            return new Style({
                image: new Circle({
                    radius: 4,
                    fill: new Fill({
                        color: 'rgba(0, 255, 255, 1)'
                    }),
                    stroke: new Stroke({
                        color: 'rgba(192, 192, 192, 1)',
                        width: 2
                    }),
                }),
                text: new Text({
                    font: '12px sans-serif',
                    textAlign: 'left',
                    text: feature.get('name'),
                    offsetY: -15,
                    offsetX: 5,
                    backgroundFill: new Fill({
                        color: 'rgba(255, 255, 255, 0.5)',
                    }),
                    backgroundStroke: new Stroke({
                        color: 'rgba(227, 227, 227, 1)',
                    }),
                    padding: [5, 2, 2, 5]
                })
            })
        },
        gotoFeature(feature) {
            this.map.getView().animate({
                center: feature.getGeometry().getCoordinates(),
                zoom: 10,
                duration: 2000,
            });
        }
    }
}
