var width = window.innerWidth / 3,
    height = width,
    distance_thresh = 1.1;

function draw_network(centor, neighbors, svg){
    // console.log(centor);
    // neighbors.forEach(function (neighbor) {
    //     console.log(neighbor.name);
       
    //     console.log(neighbor.similarity);
    // });

    var color = d3.scale.category20();

    var force = d3.layout.force()
        .charge(-500)
        .size([width, height]);

    var nodes = [];
    var links = [];
    nodes.push({"name":centor,"group":0,"distance":0});
    neighbors.forEach(function(neighbor) {
    if(neighbor.similarity < distance_thresh){
      // console.log(neighbor.name);
      // console.log(neighbor.similarity);
      nodes.push({"name":neighbor.name,"group":Math.floor((Math.random() * 10) + 1),"distance":distance_thresh-neighbor.similarity});
      links.push({"source":0,"target":links.length+1,"value":distance_thresh-neighbor.similarity});
    }
    });
    var graph = {}
    graph["nodes"] = nodes;
    graph["links"] = links;
    force
      .nodes(graph.nodes)
      .links(graph.links)
      .linkDistance(function(d){ return width/1.3/distance_thresh*d.value;})
      .start();

    var link = svg.selectAll(".link")
      .data(graph.links)
    .enter().append("line")
      .attr("class", "link")
      .style("stroke-width", function(d) { return 2 * Math.sqrt(distance_thresh - d.value); });


    var node = svg.selectAll(".node").data(nodes);
    node.exit().remove();

    var nodeEnter = node.enter().append("g")
      .attr("class", "node")
      .on("click", function(d){ if(d.group!='0') location.href = "http://adapt.seiee.sjtu.edu.cn/cdminer/query.php?query="+d.name;;})
      .call(force.drag);

    nodeEnter.append("circle")
    .attr("class", "circle")
    .attr("r", function(d){ return 35*(distance_thresh-d.distance)/distance_thresh})
    .style("fill", function(d) { if(d.group=='0') return 'yellow'; else return color(d.group); })
    .call(force.drag);

    nodeEnter.append("text")
      .text(function(d) { return d.name; })
      .attr("font-family", "sans-serif")
      .attr("font-size", "18px")
      .attr("fill", "black");



    force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });
    node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });

    // circle.attr("cx", function(d) { return d.x; })
    //     .attr("cy", function(d) { return d.y; });

    // text.attr("cx", function(d) { return d.x; })
    //     .attr("cy", function(d) { return d.y; });
    });
}

