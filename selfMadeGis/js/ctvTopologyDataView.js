let json = localStorage.getItem("tempTopologyArray");

let graph =  JSON.parse(json);


///////////////////////////////////////////////////////////////////////////////////////////

    let options = {
      layout:{randomSeed:2},
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
          network = new vis.Network(container, data, options);
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
                   function rowColor(key){
                    if(key.includes('_ou_')){
                      return 'style = "background:rgba(127,255,0,0.3);"';
                    } else { return 'style = "background-color:rgba(138,43,226,0.3)"';}
                   }
                   for (let key in row){
                          if (row[key] != null) {
                            $('#stats').find('table').append( '<tr '+rowColor(key)+'><td>'+key+'</td><td>'+row[key]+'</td></tr>');
                          }
                           
                  }
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