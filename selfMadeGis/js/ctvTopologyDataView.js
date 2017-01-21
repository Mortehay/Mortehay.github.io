let data = localStorage.getItem("tempTopologyArray");
<<<<<<< HEAD
//console.log('data',data);
let graph =  JSON.parse(data);
//console.log('restoredArray', graph);
=======
<<<<<<< HEAD
//console.log('data',data);
let graph =  JSON.parse(data);
//console.log('restoredArray', graph);
=======
console.log('data',data);
let graph =  JSON.parse(data);
console.log('restoredArray', graph);
>>>>>>> origin/master
>>>>>>> origin/master
/*if(json.links.indexOf(undefined)>-1){
  let indexUndef =json.links.indexOf(undefined);
  json.links.splice(indexUndef ,1);
  //json.nodes.splice(indexUndef ,1);
  console.log('withoutUndefined', json);
}*/
//json.links.splice(0 ,1);
//console.log('withoutFirstElement', json);
////------------------------------------
<<<<<<< HEAD
function undefinedCheck(value){
  if (value == 'undefined') { return value = 'no comment';} else {return value;}
=======
<<<<<<< HEAD
function undefinedCheck(value){
  if (value == 'undefined') { return '';} else {return value;}
>>>>>>> origin/master
}
//--------------------------------------
let svg = d3.select("svg")
    .attr("width", +$('svg').attr("width"))
    .attr("height", +$('svg').attr("height"))
    .call(d3.zoom().scaleExtent([1 / 4, 16]).on("zoom", zoomed))
  .append("g")
    .attr("transform", "translate(40,0)");

function zoomed() {
  svg.attr("transform", d3.event.transform);
}

<<<<<<< HEAD
=======
=======
let svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");
>>>>>>> origin/master
>>>>>>> origin/master
function elementSize(group){
  let bigger = [5,4,3];
  let smaller = [1,2];
  let medium = [6];
  let size = 1;
<<<<<<< HEAD
  if (bigger.indexOf(group) >-1 ) { size = 14;}
  if (smaller.indexOf(group) >-1 ) { size = 8;}
  if (medium.indexOf(group) >-1 ) { size = 10;}
=======
<<<<<<< HEAD
  if (bigger.indexOf(group) >-1 ) { size = 14;}
  if (smaller.indexOf(group) >-1 ) { size = 8;}
  if (medium.indexOf(group) >-1 ) { size = 10;}
=======
  if (bigger.indexOf(group) >-1 ) { size = 12;}
  if (smaller.indexOf(group) >-1 ) { size = 5;}
  if (medium.indexOf(group) >-1 ) { size = 8;}
>>>>>>> origin/master
>>>>>>> origin/master
  return size;
}

//let color = d3.scaleOrdinal(d3.schemeCategory20);

<<<<<<< HEAD

let simulation = d3.forceSimulation()
    .force("link", d3.forceLink().id(function(d) { return d.id; }).distance(function(d) { return 2*(d.value); })) //
    .force("charge", d3.forceManyBody().strength(-100)/*.distanceMin(function(d) { return d.value; })*/)
    .force("center", d3.forceCenter(+$('svg').attr("width") / 2, +$('svg').attr("height") / 2));


=======
<<<<<<< HEAD

let simulation = d3.forceSimulation()
    .force("link", d3.forceLink().id(function(d) { return d.id; }).distance(function(d) { return 2*(d.value); })) //
    .force("charge", d3.forceManyBody().strength(-100)/*.distanceMin(function(d) { return d.value; })*/)
    .force("center", d3.forceCenter(+$('svg').attr("width") / 2, +$('svg').attr("height") / 2));


=======
let simulation = d3.forceSimulation()
    .force("link", d3.forceLink().id(function(d) { return d.id; }))
    .force("charge", d3.forceManyBody())
    .force("center", d3.forceCenter(width / 2, height / 2));
>>>>>>> origin/master
>>>>>>> origin/master

  let link = svg.append("g")
      .attr("class", "links")
    .selectAll("line")
    .data(graph.links)
    .enter().append("line")
<<<<<<< HEAD
      .attr("stroke-width", function(d) { return Math.sqrt(d.value); })
      .attr('marker-end','url(#arrowhead)')
      .style('stroke', function(d) { return d.color; });
=======
<<<<<<< HEAD
      .attr("stroke-width", function(d) { return Math.sqrt(d.value); })
      .attr('marker-end','url(#arrowhead)')
      .style('stroke', function(d) { return d.color; });
=======
      .attr("stroke-width", function(d) { return Math.sqrt(d.value); });
>>>>>>> origin/master
>>>>>>> origin/master

  let node = svg.append("g")
      .attr("class", "nodes")
    .selectAll("circle")
    .data(graph.nodes)
    .enter().append("circle")
<<<<<<< HEAD
      .attr("class", function(d){ return 'equipment'+ ' ' +d.equipment;})
      .attr("data-id", function(d){ return d.id;})
=======
<<<<<<< HEAD
      .attr("class", function(d){ return 'equipment'+ ' ' +d.equipment;})
      .attr("data-id", function(d){ return d.id;})
=======
>>>>>>> origin/master
>>>>>>> origin/master
      .attr("r", function(d) { return elementSize(d.group); })
      .attr("fill", function(d) { return d.color; })
      .style('stroke', '#000000')
      .style('stroke-width', 1)
<<<<<<< HEAD

=======
<<<<<<< HEAD

=======
>>>>>>> origin/master
>>>>>>> origin/master
      .call(d3.drag()
          .on("start", dragstarted)
          .on("drag", dragged)
          .on("end", dragended));

  node.append("title")
<<<<<<< HEAD
      .text(function(d) { return d.name+' - '+ d.street + ', #' + d.number + '  ' + undefinedCheck(d.comment) ; });
=======
<<<<<<< HEAD
      .text(function(d) { return d.name+' - '+ d.street + ', #' + d.number + '  ' + d.comment ; });
>>>>>>> origin/master

// Define the div for the tooltip
let div = d3.select("svg").append("div") 
    .attr("class", "tooltip")       
    .style("opacity", 0);
<<<<<<< HEAD
=======
=======
      .text(function(d) { return d.name; });
>>>>>>> origin/master
>>>>>>> origin/master

  let nodelabels = svg.selectAll(".nodelabel") 
       .data(graph.nodes)
       .enter()
       .append("text")
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> origin/master
         .style("fill", "black")
         .attr("dx", 8)
         .attr("dy", ".35em")
         .style("font-size", 12)
<<<<<<< HEAD
       .html(function(d) { return '<tspan dx = "1.2em" dy="0" fill="'+ d.color + '">'+d.equipment + '</tspan><tspan dx = "-' + (7*d.equipment.length) + '" dy="1.2em"  fill="'+ d.color + '">' +  undefinedCheck(d.coment) + '</tspan>';}); 

  simulation
      .nodes(graph.nodes)
      .on("tick", ticked)
           
=======
       .html(function(d) { return '<tspan dx = "1em" dy="0" fill="'+ d.color + '">'+d.name + '</tspan><tspan dx = "-' + (3.5*d.name.length) + '" dy="1.2em"  fill="'+ d.color + '">' +  undefinedCheck(d.coment) + '</tspan>';}); 

  simulation
      .nodes(graph.nodes)
      .on("tick", ticked)
           
=======
       .style("fill", "black")
       .attr("dx", 8)
       .attr("dy", ".35em")
       .style("font-size", 12)
       .text(function(d) { return d.coment; });

  simulation
      .nodes(graph.nodes)
      .on("tick", ticked);
>>>>>>> origin/master
>>>>>>> origin/master

  simulation.force("link")
      .links(graph.links);

  function ticked() {
    link
        .attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node
        .attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });

    nodelabels.attr("x", function(d) { return d.x; }) 
                  .attr("y", function(d) { return d.y; });
  }


function dragstarted(d) {
  if (!d3.event.active) simulation.alphaTarget(0.8).restart();
  d.fx = d.x;
  d.fy = d.y;
}

function dragged(d) {
  d.fx = d3.event.x;
  d.fy = d3.event.y;
}

function dragended(d) {
  if (!d3.event.active) simulation.alphaTarget(0);
  d.fx = null;
  d.fy = null;
}
<<<<<<< HEAD
 /*function archiveLink(value, key){
      let linkArr = ['archive_link'];
      if ( (key =='archive_link') ) { value ='<a href"'+value+'">archive link</a>' ;}
      return value;

    }*/
=======
<<<<<<< HEAD

>>>>>>> origin/master
///////////////////////////////////////////////////////////////////////////////////////////

  $('.equipment').on('click', function(){
    console.log('click');
    $('#stats').remove();
<<<<<<< HEAD
    //let cubic_code = $(this).data('id');
    //let city = $('#selectedCity').text();
    let dataRequest = {
      city:$('#selectedCity').text(),
      cubic_code:$(this).data('id'),
      url:'ctvEquipmentData.php'
    }
    //ajax request------------------------------------------
    $.ajax({
      url: dataRequest.url, //This is the current doc
      type: 'POST',
      data: (dataRequest),
      success: function(data){
        //console.log(data);
        let test =  JSON.parse(data);
        console.log('test',test);
         $('body').append('<div id ="stats" style="position:fixed; top:30px; left:5px;width:200px;height:300px;background: rgba(25, 25, 25, .3);z-index:100"></div>');;
         $('#stats').append('<table></table>');
         $('#stats').find('table').append('<tr><th>Item</th><th>Value</th></tr>');
        
         let row = test.equipment[0];
         console.log('row',row);
         for (let key in row){

                 $('#stats').find('table').append( '<tr><td>'+key+'</td><td>'+row[key]+'</td></tr>');
        }
         //row.forEach(rowMaker);
      }
        
    }); 
    
    //--------------------------------------------------------
  })
=======
    $('body').append('<div id ="stats" style="position:fixed; top:30px; left:5px;width:200px;height:300px;background: rgba(25, 25, 25, .3);z-index:100"><h6 style="color:black;">'+$(this).data('id')+'</h6></div>');
  })
=======
>>>>>>> origin/master
>>>>>>> origin/master
