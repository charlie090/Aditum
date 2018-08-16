<?php
  include_once 'header.php';
?>

<style>
body {
    font: 10px sans-serif;
}

svg {
  display: block;
  margin: auto;
}

.axis path,
.axis line {
    fill: none;
    stroke: #000;
    shape-rendering: crispEdges;
}

.area {
    fill: steelblue;
}
</style>

<script src="https://d3js.org/d3.v4.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<section class = "main-container">
  <div class = "main-wrapper">
    <h2>Research</h2>
    <?php
      if (isset($_SESSION['u_id'])) {
     ?>
    <body>
        <select id = "dropDown"></select>
        <svg width="960" height="500"></svg>
      <script>

      //Create canvas
      var svg = d3.select("svg"),
          margin = {top: 20, right: 20, bottom: 30, left: 50},
          width = +svg.attr("width") - margin.left - margin.right,
          height = +svg.attr("height") - margin.top - margin.bottom,
          g = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")");

      //Parse date
      var parseDate = d3.timeParse("%Y-%m-%d");

      //Set the ranges
      var x = d3.scaleTime()
          .range([0, width]);
      var y = d3.scaleLinear()
          .range([height, 0]);

      //Define the line
      var line = d3.line()
          .x(function(d) { return x(d.date); })
          .y(function(d) { return y(d.value); });

      //Function to draw graph
      function drawGraph(data){

        //Scale the range of the data
        x.domain(d3.extent(data, function(d) { return d.date; }));
        y.domain(d3.extent(data, function(d) { return d.value; }));

        //Create x-axis
        g.append("g")
            .attr("id", "xaxis")
            .attr("transform", "translate(0," + height + ")")
            .call(d3.axisBottom(x))
          .select(".domain");

        //Create y-axis
        g.append("g")
            .attr("id", "yaxis")
          .call(d3.axisLeft(y))
            .append("text")
            .attr("fill", "#000")
            .attr("transform", "rotate(-90)")
            .attr("y", 6)
            .attr("dy", "0.71em")
            .attr("text-anchor", "end")
            .text("Price");

        //Draw line
        g.append("path")
            .datum(data)
            .attr("fill", "none")
            .attr("stroke", "steelblue")
            .attr("stroke-linejoin", "round")
            .attr("stroke-linecap", "round")
            .attr("stroke-width", 1.5)
            .attr("d", line);
      }

      d3.json("api.php", function(error, data) {
        if (error) throw error;
        data.forEach(e => {
          e.date = parseDate(e.date);
          e.value = +e.close;
          e.stockName = e.stock_name;
          e.stockSymbol = e.stock_symbol;
        });

        //Create nest variable
        var nest = d3.nest()
            .key(function(d){
              return d.stockSymbol;
            })
            .entries(data);
        console.log(nest)
        //Function to change graph
        function changeGraph(){
          //Delete existing chart items
          d3.selectAll(".line").remove()
          d3.selectAll("#xaxis").remove()
          d3.selectAll("#yaxis").remove()

          //Get value from dropdown
          var sect = document.getElementById("ddSel");
          var selectedStock = sect.options[sect.selectedIndex].value;

          drawGraph(sect)

        }

        //Create dropdown menu
        var dropDown = d3.select("select")
            .attr("id", "ddSel");

        //Create options in dropdown menu
        var options = dropDown.selectAll("option")
            .data(nest)

        options.enter()
          .append("option")
          .text(function(d){return d.key})
          dropDown.on("change", changeGraph)

        //Draw first graph
        drawGraph("1301.T")

      });



      </script>
    </body>

    <?php
  } else {
     ?>
    <p>This page is only viewable to users who are logged in.</p>
    <?php
  }
     ?>
  </div>
</section>
<?php
  include_once 'footer.php';
?>
