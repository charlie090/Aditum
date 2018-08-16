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
    border-style:solid;
    border-width:3px;
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
        <div>
          <div width = "100" height = "50" id = "dropDown"></div>
        </div>
      </body>
        <script>

        //Set dimensions and margins for the graph
        var margin = {top: 20, right: 20, bottom: 100, left: 70},
            width = 960 - margin.left - margin.right,
            height = 500 - margin.top - margin.bottom;

        //Create canvas
        var svg = d3.select("body").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
          .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        //Parse date
        var parseDate = d3.timeParse("%Y-%m-%d");

        //Set the ranges
        var x = d3.scaleTime()
            .range([0, width]);
        var y = d3.scaleLinear()
            .range([height, 0]);

        // Define the line
        var valueLine = d3.line()
            .x(function(d) { return x(d.date); })
            .y(function(d) { return y(+d.value); });

        //Import data from api
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
              .key(function(d){ return d.stockSymbol; })
              .entries(data);

          console.log(nest);
          //Scale the range of the data
          x.domain(d3.extent(data, function(d) { return d.date; }));
          //y.domain([0, d3.max(data, function(d) { return d.value; })]);

          //Add the x axis
          var xaxis = svg.append("g")
              .attr("transform", "translate(0," + height + ")")
              .attr("class", "x axis")
              .call(d3.axisBottom(x));

          //Add x axis label
          svg.append("text")
              .attr("transform", "translate(" + (width/2) + "," + (height + margin.top + 10) + ")")
              .attr("dy", "1em")
              .style("text-anchor", "middle")
              .text("Date")
              .attr("class", "x axis label");

          //Create dropdown
          var dropDown = d3.select("#dropDown")
          dropDown
      		.append("select")
      		.selectAll("option")
              .data(nest)
              .enter()
              .append("option")
              .attr("value", function(d){ return d.key; })
              .text(function(d){ return d.key; })

          // Function to create the initial graph
         	var initialGraph = function(stock){

         		// Filter the data to include only stock of interest
         		var selectStock = nest.filter(function(d){
                        return d.key == stock;
                      })
              //Draw y axis
        	    var selectStockGroups = svg.selectAll(".stockGroups")
        		    .data(selectStock, function(d){
        		      return d ? d.key : this.key;
        		    })
        		    .enter()
        		    .append("g")
        		    .attr("class", "stockGroups")
        		    .each(function(d){
                        y.domain([0, d3.max(data, function(d) { return d.value; })])
                    });

        		var initialPath = selectStockGroups.selectAll(".line")
        			.datum(function(d) { return d.values; })
        			.enter()
        			.append("path")

        		initialPath
        			.attr("d", function(d){ return valueLine(d.values) })
        			.attr("class", "line")

        		  //Add the y axis
              var yaxis = svg.append("g")
                  .attr("class", "y axis")
      		        .call(d3.axisLeft(y)
      		          .ticks(5)
      		          .tickSizeInner(0)
      		          .tickPadding(6)
      		          .tickSize(0, 0));

        		  //Add y axis label
        		  svg.append("text")
        		        .attr("transform", "rotate(-90)")
        		        .attr("y", 0 - 60)
        		        .attr("x", 0 - (height / 2))
        		        .attr("dy", "1em")
        		        .style("text-anchor", "middle")
        		        .text("Price")
        		        .attr("class", "y axis label");
         	}

          // Create initial graph
         	initialGraph("1301.T")

         	// Update the data
         	var updateGraph = function(stock){

         		// Filter the data to include only stock of interest
         		var selectStock = nest.filter(function(d){
                        return d.key == stock;
                      })

         		// Select all of the grouped elements and update the data
        	    var selectStockGroups = svg.selectAll(".stockGroups")
        		    .datum(selectStock)
        		    .each(function(d){
                        y.domain([0, d3.max(data, function(d) { return d.value; })])
                    });

        		    // Select all the lines and transition to new positions
                    selectStockGroups.selectAll("path.line")
                       .data(function(d) { return d.values; },
                       		function(d){ return d.key; })
                       .transition()
                          .duration(1000)
                          .attr("d", function(d){
                            return valueLine(d.values)
                          })

                // Update the Y-axis
                    d3.select(".y")
                            .transition()
                            .duration(1500)
                            .call(d3.axisLeft(y)
                              .ticks(5)
                              .tickSizeInner(0)
                              .tickPadding(6)
                              .tickSize(0, 0));
         	}
          // Run update function when dropdown selection changes
          dropDown.on('change', function(){

            // Find which stock was selected from the dropdown
            var selectedStock = d3.select(this)
                    .select("select")
                    .property("value")

                // Run update function with the selected stock
                updateGraph(selectedStock)
          });
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
