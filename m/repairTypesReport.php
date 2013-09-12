<?php
	// Verify input, otherwise ask for it
	if(!isset($_GET['reportYear'])) {
		$alert['msg'] = "Please select a report values";
		$content = "<h1>Select a year</h1>";
		$content .= "<form action='repairTypesReport.php' method='GET'>" .
			"<select name='reportYear'>" .
			"<option value='2009'>2009</option>" .
			"<option value='2010'>2010</option>" .
			"<option value='2011'>2011</option>" .
			"<option value='2012'>2012</option>" .
			"<option value='2013'>2013</option>" .
			"<option value='0'>All Time</option>" .
			"</select><input type='submit'></form>";

        } else {
	// Step 1 Gather Repair Ticket Types from the database
	$repairTypes = $framework->get('tickets')->getTypes();
	if(!$repairTypes) {
		$alert['msg']="Repair Types are jacked...";
		$alert['status']="error";
		return;
	}

	// Build a new array 
	$repairs = array();
	foreach($repairTypes as $id => $repairType) {
                $repairTypes[$id]['count'] = 0;
        }

	// Step 2 Gather the Repairs per tickets in the database
	$result = $framework->get('tickets')->getAll($_GET['reportYear']);

	foreach($result as $row) {
                if(isset($row['ticket.repair'])) {
                	$repairTypes[$row['ticket.repair']]['count']++;
                }
	} 

	// Step 3 Present the details with Google Charts
?>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['', 'Repair Type Results'],
<?php
foreach($repairTypes as $repair) {
        $string_to_display = $repair['description']." (".$repair['count'].")"; 
	printf("      [\"%s\",%s],\n",$string_to_display,$repair['count']);
}
?>
      ]);

      var options = {
        title: 'Repair Types',
        is3D: true,
        slices: {
          7: {color: '#006EFF'},
          8: {color: '#00FF08'}
        }
      };

      var chart = new google.visualization.PieChart(document.getElementById('chart_referrences'));
        chart.draw(data, options);
      }
    </script>
<?php
	$content = '<h2>Repair Report</h2>';
	$content.= '<div id="chart_referrences" style="width: 600px; height: 275px;"></div>';
	//$content.= '<div id="chart_open" style="width: 600px; height: 275px;"></div>';
	}
?>
