<?php
	// Step 1 Gather Referrals from the referral table in the database
	$referrences = $framework->get('customers')->getReferrals();
	if(!$referrences) {
		$alert['msg']="Referrences are jacked...";
		$alert['status']="error";
		return;
	}
	// Build a new array :)
	$referrals = array();
	foreach($referrences as $id => $referrence) {
		$referrals[$referrence['reff.id']]= array(
			'id'=>$referrence['reff.id'],
			'count'=>0,
			'referral'=>$referrence['reff.reff']
		);
	}
	unset($referrences);

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
