<?php
	// Step 1 Gather Repair Ticket Types from the database
	$repairTypes = $framework->get('tickets')->getTypes();
	if(!$repairTypes) {
		$alert['msg']="Repair Types are jacked...";
		$alert['status']="error";
		return;
	}
	echo "<pre>";
	print_r($repairTypes);
	echo "</pre>";
	// Build a new array 
	$repairs = array();
	foreach($repairTypes $id => $repairs) {
		$repairs[$repairTypes['tickettypes.id']]= array(
			'id'=>$repairTypes['tickettypes.id'],
			'count'=>0,
			'repairType'=>$repairTypes['tickettypes.description']
		);
	}

	// Step 2 Gather the referrals per customer in the database
	$result = $framework->get('customers')->getAll();
	foreach($result as $row) {
		$referrals[$row['customer.referral']]['count']++;
	}

	// Step 3 Present the details with Google Charts
?>
<script type="text/javascript">
  google.load("visualization", "1", {packages:["corechart"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['', 'Customer Source Results'],
<?php
foreach($referrals as $referral) {
	printf("      [\"%s\",%s],\n",$referral['referral'],$referral['count']);
}
?>
      ]);

      var options = {
        title: 'Customer Sources',
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
	$content = '<h2>Google Charts</h2>';
	$content.= '<div id="chart_referrences" style="width: 600px; height: 275px;"></div>';
	//$content.= '<div id="chart_open" style="width: 600px; height: 275px;"></div>';
?>
