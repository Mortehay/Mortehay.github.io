let data = localStorage.getItem("tempTopologyArray");
console.log('data',data);
let graph =  JSON.parse(data);
console.log('restoredArray', graph);
/*if(json.links.indexOf(undefined)>-1){
  let indexUndef =json.links.indexOf(undefined);
  json.links.splice(indexUndef ,1);
  //json.nodes.splice(indexUndef ,1);
  console.log('withoutUndefined', json);
}*/
//json.links.splice(0 ,1);
//console.log('withoutFirstElement', json);
////------------------------------------
let svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");
function elementSize(group){
  let bigger = [5,4,3];
  let smaller = [1,2];
  let medium = [6];
  let size = 1;
  if (bigger.indexOf(group) >-1 ) { size = 12;}
  if (smaller.indexOf(group) >-1 ) { size = 5;}
  if (medium.indexOf(group) >-1 ) { size = 8;}
  return size;
}

//let color = d3.scaleOrdinal(d3.schemeCategory20);

let simulation = d3.forceSimulation()
    .force("link", d3.forceLink().id(function(d) { return d.id; }))
    .force("charge", d3.forceManyBody())
    .force("center", d3.forceCenter(width / 2, height / 2));

  let link = svg.append("g")
      .attr("class", "links")
    .selectAll("line")
    .data(graph.links)
    .enter().append("line")
      .attr("stroke-width", function(d) { return Math.sqrt(d.value); });

  let node = svg.append("g")
      .attr("class", "nodes")
    .selectAll("circle")
    .data(graph.nodes)
    .enter().append("circle")
      .attr("r", function(d) { return elementSize(d.group); })
      .attr("fill", function(d) { return d.color; })
      .style('stroke', '#000000')
      .style('stroke-width', 1)
      .call(d3.drag()
          .on("start", dragstarted)
          .on("drag", dragged)
          .on("end", dragended));

  node.append("title")
      .text(function(d) { return d.name; });

  let nodelabels = svg.selectAll(".nodelabel") 
       .data(graph.nodes)
       .enter()
       .append("text")
       .style("fill", "black")
       .attr("dx", 8)
       .attr("dy", ".35em")
       .style("font-size", 12)
       .text(function(d) { return d.coment; });

  simulation
      .nodes(graph.nodes)
      .on("tick", ticked);

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
