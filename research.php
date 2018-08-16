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

.controller {
  position: relative;
  display: flex;
  justify-content: center;
  .control {
    label {
      font-weight: bold;
    }
    input[type = "range"]{
    }
  }
}
</style>

<script src="https://d3js.org/d3.v4.min.js"></script>
<section class = "main-container">
  <div class = "main-wrapper">
    <h2>Research</h2>
    <?php
      if (isset($_SESSION['u_id'])) {
     ?>
    <body>
        <div width = "100" height = "50" id = "dropdown"></div>
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

      d3.json("api_all.php", function(error, data) {
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

      // Scale the range of the data
      x.domain(d3.extent(data, function(d) { return d.date; }));
      //y.domain([0, d3.max(data, function(d) { return d.value; })]);

      // Set up the x axis
      var xaxis = svg.append("g")
           .attr("transform", "translate(0," + height + ")")
           .attr("class", "x axis")
           .call(d3.axisBottom(x));
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
