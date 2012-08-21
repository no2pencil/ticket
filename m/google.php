<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['', 'Customer Source Results'],
<?php
$results = $framework->get('customers')->reff();
if(!$results) {
  die(":(");
}
$unknow=0;
$coffee=0;
$user=0;
$phone=0;
$radio=0;
$dollar=0;
$direct=0;
$mouth=0;
$sign=0;
$newspaper=0;
$facebook=0;
$result = $framework->get('customers')->search("");
foreach($result as $row) {
	//echo "<!-- ID :".$i." Reff :".$row[referral]."<br> -->";
	switch($row[referral]) {
		case 0:
			$coffee++;
			break;
		case 1:
			$coffee++;
			break;
		case 2:
			//$user++;
			//break;
		case 3:
			//$phone++;
			$user++;
			break;
		case 4:
			$radio++;
			break;
		case 5:
			$dollar++;
			break;
		case 6:
			$direct++;
			break;
		case 7:
			$mouth++;
			break;
		case 8:
			$sign++;
			break;
		case 9:
			$google++;
			break;
		case 10:
			$facebook++;
			break;
		case 11:
			$newspaper++;
			break;
	}
}
$i=0;
$result = array_unique($results);
$seo=array();
while($i<15) {
	if($i==1) $seo[0]=$coffee;
	if($i==2) $seo[1]=$user;
	if($i==3) $seo[2]=$phone;
	if($i==4) $seo[3]=$radio;
	if($i==5) $seo[4]=$dollar;
	if($i==6) $seo[5]=$direct;
	if($i==7) $seo[6]=$mouth;
	if($i==8) $seo[7]=$sign;
	if($i==9) $seo[8]=$google;
	if($i==10) $seo[9]=$facebook;
	if($i==11) $seo[10]=$newspaper;
	$i++;
}
$i=0;
foreach($result as $row) {
	printf("          [\"%s\",	%s],\n",$row[reff],$seo[$i]);
	$i++;
}

/*
|  1 | Coffee news                     |
|  2 | User Friendly Phone Book        |
|  3 | Yellow Pages                    |
|  4 | WNIR                            |
|  5 | JB Dollar Stretcher             |
|  6 | Direct Contact                  |
|  7 | Word of mouth                   |
|  8 | Sign                            |
|  9 | Google                          |
| 10 | Facebook Fan Page               |
| 11 | Cuyahoga Falls Record Publisher
*/
?>
       ]);

        var options = {
          title: 'Customer Sources'
        };

        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
<?php
  $content = '<h2>Google Charts</h2>';
  $content.= '<div id="chart_div" style="width: 900px; height: 500px;"></div>';
  echo $chart_content;
?>
