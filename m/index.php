<?php

$data = $framework->get('tickets')->getAllOpen();
$customer = array();
if(!$data) {
	$content .= "No currently open tickets...";
}
else {
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
		$content .= '</a></td>';
		$content .= '<td>' . $ticket['status'] . '</td>';
		$content .= '</tr>';
        }
        $content .= '
                                        </tbody>
                                </table>';
}
?>
