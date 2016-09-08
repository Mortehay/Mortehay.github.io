

//center point of map
var centerPoint=[
  {
    lon:33.299560546875,
    lat:48.545705491847464,
    zoom:6
  }
];
//test points
//https://api.myjson.com/bins/20na7

var defaultPointStyle = new ol.style.Style({
    image: new ol.style.Circle({
      radius: 10,
      fill: new ol.style.Fill({color: '#66ffff'}),
      stroke: new ol.style.Stroke({
          color: [101, 95, 90, 1],
          width: 1
        })  
    })
  })
var newPointClickStyle= new ol.style.Style({
    image: new ol.style.Circle({
      radius: 10,
      fill: new ol.style.Fill({color: '#ff5050'}),
      stroke: new ol.style.Stroke({
          color: [204, 102, 153, 1],
          width: 3
        })  
    })
  })
var newPointHoverStyle= new ol.style.Style({
    image: new ol.style.Circle({
      radius: 10,
      //fill: new ol.style.Fill({color: '#ccff33'}), causes difficulty in click on point
      stroke: new ol.style.Stroke({
          color: [153, 102, 51, 1],
          width: 3
        })  
    })
  })

// Create layers instances
var osmLayer = new ol.layer.Tile({
    source: new ol.source.OSM({
        layer: 'osm'
    }),
    name: 'OSM'
});
// a vector layer to render the source
var testPoints = new ol.layer.Vector({
  source: new ol.source.Vector({
    url: 'https://api.myjson.com/bins/403w3',
    extractStyles: false,
    projection: 'EPSG:3857',
    format: new ol.format.GeoJSON()
   

  }),
  id:'srm',
  name: 'testPoints',
  style: defaultPointStyle/**/
});

/*testPoints.events.on({
  loadend: function() { console.log(testPoints.getSource().getFeatures());}
});*/

/*var vectorSource = testPoints.getSource();
var listenerKey = vectorSource.on('change', function(e) {
  if (vectorSource.getState() == 'ready') {
    var featureCount = vectorSource.getFeatures().length;
    // ...
    console.log(featureCount);
    
    console.log(vectorSource.getFeatures());
    console.log(vectorSource.getExtent());
    console.log(vectorSource.getExtent());
    ol.Observable.unByKey(listenerKey);
    // use vectorSource.unByKey(listenerKey) instead
    // if you do use the "master" branch of ol3
  }
});*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////
var url = 'https://api.myjson.com/bins/20na7';
var r = new XMLHttpRequest();
r.open("GET", url, true);
r.onreadystatechange = function () {
    if (r.readyState != 4 || r.status != 200) {
      obj = JSON.parse(r.responseText);
        return obj ;
    }
    console.log(r.responseText);
};
r.send();


console.log(obj);

var features = obj.features;
for (var i = 0, len = features.length; i < len; i++) {
    var feat = features[i].properties;
    feat['type'] = features[i].geometry.type;
    feat['coordinates'] = features[i].geometry.coordinates;
    features_data_array.push(feat);
}
console.log(features_data_array);
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
var selectInteraction = new ol.interaction.Select({
        condition: ol.events.condition.singleClick,
        toggleCondition: ol.events.condition.singleClick,//shiftKeyOnly
        layers: [
          (function (layer) {

            return layer.get('id') == 'srm';
          })],
        style: newPointClickStyle
      });

// Create map
var map = new ol.Map({
    target: 'map',  // The DOM element that will contains the map
    renderer: 'canvas', // Force the renderer to be used
    layers: [osmLayer, testPoints],
    // Create a view centered on the specified location and zoom level
    view: new ol.View({
        center: ol.proj.transform([centerPoint[0].lon, centerPoint[0].lat], 'EPSG:4326', 'EPSG:3857'),
        zoom: centerPoint[0].zoom
    })
});

map.getInteractions().extend([selectInteraction]);

var highlightStyleCache = {};

//layer style
function styleFunction(feature, resolution) {
        var style;
        var geom = feature.getGeometry();
        if (geom.getType() == 'Point') {
          var text = feature.get('name');
          baseTextStyle.text = text;
          // this is inefficient as it could create new style objects for the
          // same text.
          // A good exercise to see if you understand would be to add caching
          // of this text style
          
          style = defaultPointStyle;
        } else {
          style = newPointStyle;
        }

        return [style];
      }

      var featureOverlay = new ol.FeatureOverlay({
        map: map,
        style: styleFunction
      });

      // when the mouse moves over the map, we get an event that we can use
      // to create a new feature overlay from
var featureOverlay = new ol.FeatureOverlay({
  map: map,
  style: function(feature, resolution) {
    var text = resolution < 5000 ? feature.get('name') : '';
    if (!highlightStyleCache[text]) {
      highlightStyleCache[text] = [newPointHoverStyle];
    }
    return highlightStyleCache[text];
  }
});

var highlight;
var displayFeatureInfo = function(pixel) {

  var feature = map.forEachFeatureAtPixel(pixel, function(feature, layer) {
    return feature;
  });

  if (feature !== highlight) {
    if (highlight) {
      featureOverlay.removeFeature(highlight);
    }
    if (feature) {
      featureOverlay.addFeature(feature);
    }
    highlight = feature;
  }

};

map.on('pointermove', function(evt) {
  if (evt.dragging) {
    return;
  }
  var pixel = map.getEventPixel(evt.originalEvent);
  displayFeatureInfo(pixel);
});

/*map.on('click', function(evt) {
  displayFeatureInfo(evt.pixel);
});
*/




$( document ).ready(function(){


 
});	
	
	
 
//----------------------------------------------------


//jquery plugins--------------------------------------
(function($){
//checker-----------------------------------------------

          





//----------------------------------------------------- 
	
})(jQuery);