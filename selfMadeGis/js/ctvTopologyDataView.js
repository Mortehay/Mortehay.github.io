let json = localStorage.getItem("tempTopologyArray");

let graph =  JSON.parse(json);
$('body').prepend(rotatingArrows);


///////////////////////////////////////////////////////////////////////////////////////////
function closeSpan(index){
  $('.'+index).prepend('<span class="closeSpan"></span>');
  $('.closeSpan').on('click', function(){
    console.log('click');
    $(this).parent().remove();
  })
}
///////////////////////////////////////////////////////////////////////////////////////////
function doAnimation(nodeParams) {
       let moveOptions = {
            // position: {x:positionx,y:positiony}, // this is not relevant when focusing on nodes
            scale: parseFloat(nodeParams.scale),
            offset: {x:parseInt(nodeParams.offsetx),y:parseInt(nodeParams.offsety)},
            locked:false,
            //position: {x:parseInt(nodeParams.positionx),y:parseInt(nodeParams.positiony)},
            animation: {
                duration: parseInt(nodeParams.duration),
                easingFunction: nodeParams.easingFunction
            }
        }
        //statusUpdateSpan.innerHTML = "Focusing on node: " + nodeId;
        //finishMessage = "Node: " + nodeId + " in focus.";
      network.focus(parseInt(nodeParams.nodeId),moveOptions);
    }
///////////////////////////////////////////////////////////////////////////////////////////

    let options = {
      layout:{randomSeed:7},
        nodes: {
            shape: 'dot',
            size: 10,
            font: {
                size: 14,
                color: '#000000'
            },
            borderWidth: 2
        },
        edges: {
            width: 10
        },
        interaction:{hover:true},
        physics:{
                 enabled: true,
                  barnesHut: {
                    gravitationalConstant: -2000,
                    centralGravity: 0.3,
                    springLength: 95,
                    springConstant: 0.04,
                    damping: 0.09,
                    avoidOverlap: 0
                  },
                  forceAtlas2Based: {
                    gravitationalConstant: -50,
                    centralGravity: 0.01,
                    springConstant: 0.08,
                    springLength: 100,
                    damping: 0.4,
                    avoidOverlap: 0
                  },
                  repulsion: {
                    centralGravity: 0.2,
                    springLength: 200,
                    springConstant: 0.05,
                    nodeDistance: 100,
                    damping: 0.09
                  },
                  hierarchicalRepulsion: {
                    centralGravity: 0.0,
                    springLength: 100,
                    springConstant: 0.01,
                    nodeDistance: 120,
                    damping: 0.09
                  },
                  maxVelocity: 50,
                  minVelocity: 0.1,
                  solver: 'barnesHut',
                  stabilization: {
                    enabled: true,
                    iterations: 1000,
                    updateInterval: 100,
                    onlyDynamicEdges: false,
                    fit: true
                  },
                  timestep: 0.5,
                  adaptiveTimestep: true
              }
    };
    function selectorGenerator(data, selectorId){
      let mdodSelector = '<select id="mdodSelector">'+'<option value="select she">select she</option>'+'<option value="all">all</option>';
      for (let i = 0; i < data.length; i++) {
        mdodSelector +='<option value="'+data[i].she+'">'+data[i].title+'</option>';
      }
       mdodSelector += '</select>'
      $('body').prepend(mdodSelector );
      console.log('mdodArr', data);
    }
    function dataArrayAfterSelector(data,selectorId, options){
      $( '#' +selectorId).change(function(){
        let selectedValue = $(this).val();
        
        console.log('selector value', selectedValue);
        if(selectedValue =='select she'){
          $('#mynetwork').empty();
        } else if (selectedValue == 'all') {
          $('#mynetwork').empty();
          
          networkDraw(data, options);
        } else {
          $('#mynetwork').empty();
          //console.log('data',data.nodes);
         // let sortedNodes = data.nodes.filter(function( obj ) { return obj.she == selectedValue;});
          //let sortedEdges = data.edges.filter(function( obj ) { return obj.she == selectedValue;});
          
          let sortedData = {
              nodes: data.nodes.filter(function( obj ) { return obj.she == selectedValue;}),
              links: data.links.filter(function( obj ) {return obj.she == selectedValue;})
          };
          //console.log('sortedData', sortedData);
          networkDraw(sortedData, options);
        }

      });

    }
    ///////////////////////////////////////
    function nodeSelection(nodesCanvasData, nodesData){
       console.log('nodesCanvasData',nodesCanvasData);
       console.log('graph',nodesData);
          $('#nodes').remove();
          $('<input type="text" name="nodes" id="nodes" list="node_list"  style="width:250px;height:20px;background-color:#FAEBD7;"><datalist id="node_list"></datalist>').insertBefore($('#mynetwork'));
           for (let objKey in nodesCanvasData){
            if ((objKey!=null) || (objKey!='') ) {$('#node_list').append('<option value="'+objKey+'" data-x="'+nodesCanvasData[objKey].x+'" data-y="'+nodesCanvasData[objKey].y+'">'+objKey+'<option>');}
           }
            $('#nodes').keypress(function (e) {
              if (e.which == 13) {
                
                let selectedValue = $(this).val();
                  if(selectedValue.length>0){

                    console.log(selectedValue);
                    new doAnimation({
                      nodeId:$(this).val(),
                      scale:5,
                      offsetx:0,
                      offsety:0,
                      locked:true,
                      duration:2000,
                      easingFunction:'easeInOutQuart'
                  });

                  }

              }
            });
    }
    //////////////////////////////////////
    function networkDraw(graph, options){
         let color = '#000000';
      let len = undefined;
      let nodes = new vis.DataSet(graph.nodes);
           
          let edges = new vis.DataSet(graph.links);

          // create a network
          let container = document.getElementById('mynetwork');
          let data = {
              nodes: nodes,
              edges: edges
          };
          console.log('networkData',data);
          network = new vis.Network(container, data, options);
          //let nodesCanvasData = network.getPositions(); // get the data from selected node

          nodeSelection(network.getPositions(), nodes); //adding adress list selector on cubic_code number 

          network.on("click", function (params) {
              params.event = "[original event]";
              $('#stats').remove();

            let dataRequest = {
              city:$('#selectedCity').text(),
              cubic_code:params.nodes[0],
              url:'ctvEquipmentData.php'
            }
            console.log('params', params);
            console.log('dataRequest', dataRequest);
            if (params.nodes.length == 0) {
                $('#stats').remove();
              } else{
                $.ajax({
                url: dataRequest.url, //This is the current doc
                type: 'POST',
                data: (dataRequest),
                success: function(request){
                  //console.log(data);
                  let test =  JSON.parse(request);
                  console.log('test',test);
                   $('body').append('<div id ="stats" class="stats"></div>');;
                   $('#stats').append('<table></table>');
                   $('#stats').find('table').append('<tr><th>Item</th><th>Value</th></tr>');
                  
                   let row = test.equipment[0];
                   console.log('row',row);
//////////////////////function different color  for equipment and parent equipment                 
                   function rowColor(key){

                    if(key.includes('_ou_')){
                      return 'style = "background:rgba(127,255,0,0.6);"';
                    } else { return 'style = "background-color:rgba(138,43,226,0.6)"';}
                   }
///////////////////////////
///////////////////////function cude null values in table
                   function buttonAdd(key, row){
                    if(row[key].includes('cc')){return row[key] + '<button id="displayWiring" data-city="'+dataRequest.city+'" data-type="cc" data-code="'+row['cubic_code']+'">rozvarka</button>' } else { return row[key]}
                   }
                   for (let key in row){
                          if (row[key] != null) {
                            $('#stats').find('table').append( '<tr '+rowColor(key)+'><td>'+key+'</td><td>'+buttonAdd(key,row)+'</td></tr>');
                          }
                           
                  }
///////////////////////
                  $('#displayWiring').on('click', function(){
                    $('.wiring').remove();
                    $('body').prepend('<div class="wiring"><img class="backup_picture" src="../tmp/archive/'+$(this).data('city')+'/topology/'+$(this).data('type')+'/'+$(this).data('code')+'/'+$(this).data('code')+'_wiring.png">'+'</div>');
                      $('.backup_picture').on('error', function(){
                          $(this).attr('src', '../img/vguh.png');
                          $('.wiring').on('click', function(){
                            $('.wiring').hide();
                          });  
                      });
                      closeSpan('wiring');

                          //alert($(this).data('city')+' / '+$(this).data('type') +' ' +$(this).data('code'));
                  })
                   //row.forEach(rowMaker);
                }
                  
              }); 
              }
           });
          ///////////////////////////////////////////
          network.on("hoverNode", function (params) {
              console.log('hoverNode Event:', params);
              //$('#stats').remove();
          });
         
    }

    
    selectorGenerator(graph.mdods, 'selectedCity');

    dataArrayAfterSelector(graph, 'mdodSelector', options);

    //networkDraw(data, options);


///////////////////////////////////////////////////////////////////////////////////////////