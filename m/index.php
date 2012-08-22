<?php
$data = $framework->get('tickets')->getAllOpen();
$customer = array();
if(!$data){
	$content .= "No currently open tickets...";
} else {
	$content .= '<table class="table">
		<thead>
		<tr><th>Invoice</th><th>Customer</td><th>Status</th></tr>
		</thead>
		<tbody>';
        foreach($data as $key => $ticket){
                $content .= '<tr>';
		$content .= '<td><a href="tickets.php?search=' . substr($ticket['invoice'],-4) . '">'. $ticket['invoice'] . '</a></td>';
		$content .= '<td><a href="customers.php?view='.$ticket['customer'].'">';
		$customer = $framework->get('customers')->getInfoById($ticket[customer]);
		$content .= $customer['name'];
		$content .= '</a></td><td>';
		$content .= '<div class="btn-group">';
  		$content .= '<button class="btn ';
		switch($ticket['status']) {
			case 19:
				$content .= 'btn-inverse dropwdown-toggle">In Progress';
				break;
			case 18:
				$content .= 'btn-primary dropdown-toggle">Open';
				break;
			case 17:
			case 42:
			case 51:
			case 56: // DS Lite
				$content .= ' dropdown-toggle">New';
				break;
			case 48:
				$content .= 'btn-danger dropdown-toggle">Post Payment';
				break;
			case 40:
				$content .= 'btn-success dropdown-toggle">Pending Payment';
				break;
			case 70:
				$content .= 'btn-info dropdown-toggle">Waiting for Parts';
				break;
			case 71:
				$content .= 'btn-warning dropdown-toggle=">Call Customer Admin';
				break;
			case 68:
				$content .= 'btn-warning dropdown-toggle">Call Customer Tech';
				break;
			default:
				$content .= 'btn-primary data-toggle">'.$ticket['status'];
				break;
		}
		$content .= '<button class="btn dropdown-toggle" data-toggle="dropdown">';
    		$content .= '<span class="caret"></span></button>';
  		$content .= '<ul class="dropdown-menu">';
		$content .= '<li><a href="tickets.php?search='.substr($ticket['invoice'],-4).'">View</a></li>';
		$content .= '<li><a href="tickets.php?edit='.substr($ticket['invoice'],-4).'">Edit</a></li>';
		$content .= '<li class="divider"></li>';
		$content .= '<li><a href="#">Close</a></li>';
		$content .= '<li><a href="#">Cancel</a></li>';
		$content .= '</ul></div>';
		$content .= '</td></tr>';
        }
        $content .= '
                                        </tbody>
                                </table>';
}
?>
