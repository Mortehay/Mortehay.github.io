 function tool1(json){console.log(json);}
 let wiresInCable = [2,4,6,8,12,16,24,32,36,48,64];
 let wiringColor = {
  select:'',
  finmark:
{
  tube:[{text:'синій',color:'#0000ff'},{text:'помаранчевий',color:'#ff6600'},{text:'зелений',color:'#009933'},{text:'коричневий',color:'#663300'},{text:'сірий',color:'#808080'},{text:'білий',color:'#ffffff'},{text:'червоний',color:'#ff0000'},{text:'чорний',color:'#000000'},{text:'жовтий',color: '#ffff00'},{text:'фіолетовий',color: '#ee82ee'},{text:'рожевий',color:'#ffc0cb'},{text:'бірюзовий',color:'#00ffff'}],
  wires:[{text:'синій',color:'#0000ff'},{text:'помаранчевий',color:'#ff6600'},{text:'зелений',color:'#009933'},{text:'коричневий',color:'#663300'},{text:'сірий',color:'#808080'},{text:'білий',color:'#ffffff'},{text:'червоний',color:'#ff0000'},{text:'чорний',color:'#000000'},{text:'жовтий',color: '#ffff00'},{text:'фіолетовий',color: '#ee82ee'},{text:'рожевий',color:'#ffc0cb'},{text:'бірюзовий',color:'#00ffff'}]
  }
 };
 $( document ).ready(function() {


paper.install(window);
paper.setup('myCanvas');
let layer = new Layer({
    //children: [coupler],
    strokeColor: 'black',
    position: view.center
});
project.activeLayer.name = 'layer_1';
$('#wiringComposer').wiringComposer(wiresInCable,wiringColor,layer);
    let json= '';
    let path;

});
(function($){
  $.fn.wiringComposer = function(wiresInCable,wiringColor,layer){
    layer.activate();
    $(this).append('<select class="wiringNumberSelector"></select>');
    wiresInCable.forEach(function (item) {
      $('.wiringNumberSelector').append('<option value="'+item+'">'+item+'</option>');
    });
    $(this).append('<select class="wiringVendorSelector"></select>');
      for (let key in wiringColor) {
        $('.wiringVendorSelector').append('<option value="'+key+'">'+key+'</option>');
      } 
    $(this).append('<button id="couplerGeneration">генерувати</button>');
    $(this).find('#couplerGeneration').on('click', function(){
    //console.log($('.wiringVendorSelector').val());
    if( $('.wiringVendorSelector').val() !='select'){
      let wiresNum = $('.wiringNumberSelector').val();
      let vendor =  $('.wiringVendorSelector').val();
      //console.log(vendor+' wires'+wiresNum);
      function couplerGenerator(wiringType,wiresNum){
      layer.activate();
        console.log(wiringType);
       let wirePolygon;
       let wireText;
       let adapterPolygon;
       let couplerChildren;
       let tubePolygon;
       let wireTube=[];
       let coupler;
       let json;
       console.log(wiringType.wires.length);
       for (let x=0;x < Math.ceil(wiresNum/wiringType.wires.length) ; x++){
        console.log('x',x);
        let tempWireNum=0;
        for (let z = x*wiringType.wires.length; z < wiresNum; z++) {
           wirePolygon = new Path.Rectangle({
                point: [20, 20+z*20],
                size: [100, 20],
                name: x+'_'+wiresNum,
                strokeColor: 'black',
                fillColor:wiringType.wires[z%(wiringType.wires.length)].color
            });
            adapterPolygon = new Path.Rectangle({
                point: [115, 25+z*20],
                size: [10, 10],
                name: x+'_a_'+wiresNum,
                strokeColor: 'black',
                fillColor:'white'
            });
            wireText = new PointText({
                point: [25, 30+z*20],
                content: wiringType.wires[z%(wiringType.wires.length)].text,
                name: x+'_w_'+wiresNum,
                fillColor: function(wiringType,z){
                    if(wiringType.wires[z%(wiringType.wires.length)].color == '#000000'){
                      return  '#ffffff';
                    } else {return '#000000';}
                  },
                fontFamily: 'Arial',
                //fontWeight: 'bold',
                fontSize: 13
            });
            
            wireTube.push(wirePolygon,wireText,adapterPolygon);
            tempWireNum =tempWireNum+ 1;
        }
        let temp_x;
        //if(x<1){temp_x =0} else {temp_x =1}
        tubePolygon = new Path.Rectangle({
                point: [10, 20+(x*wiringType.wires.length)*20],
                size: [10, (tempWireNum)*20],
                name: x+'_tube',
                strokeColor: 'black',
                fillColor:wiringType.tube[x].color
            });
        wireTube.push(tubePolygon);
        coupler = new Group({
          children: wireTube,
          strokeColor: 'black',
          // Set the stroke color of all items in the group:
          //fillColor: wiringType.tube[x].color,
          // Move the group to the center of the view:
          //position: view.center
        });
       }
      coupler.name = 'coupler_1';
//group.selected = false;
coupler.pivot = coupler.center;
coupler.onMouseDrag = function(event) {
    coupler.position = coupler.position.subtract(coupler.position).add(event.point);
      coupler.pivot = event.point;
      
       json = paper.project.activeLayer.exportJSON();
      //tool1(json);
      return json;
  };
      layer.children.push(coupler); 
       view.draw(); 
       $('#jsonCreate').on('click', function(){
  if(json!=''){
    console.log(json);
  } });
  $('#layerClear').on('click', function(){
    project.activeLayer.removeChildren();
    json ='';
  });
      }
      couplerGenerator(wiringColor[vendor],wiresNum);
    } else {console.log('виберіть виробника');}
      
    })
  }
})(jQuery);

