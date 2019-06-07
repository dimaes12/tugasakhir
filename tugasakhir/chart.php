<script src="https://static.anychart.com/js/8.0.1/anychart-core.min.js"></script>
<script src="https://static.anychart.com/js/8.0.1/anychart-pie.min.js"></script>
<script>
    
    anychart.onDocumentReady(function() {

  // set the data
  var data = [
      {x: "Negatif", value: 3},
      {x: "Positif", value: 19, exploded: true}
  ];

  // create the chart
  var chart = anychart.pie();

  // set the chart title
  chart.title("Hasil Real Count");

  // add the data
  chart.data(data);

  // sort elements
  chart.sort("desc");  

  // set legend position
  chart.legend().position("right");
  // set items layout
  chart.legend().itemsLayout("vertical");  

  // display the chart in the container
  chart.container('container');
  chart.draw();

});

</script>