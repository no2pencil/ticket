<?php
$content .= '<h2>Home</h2>';

$tickets = $framework->get('tickets')->getAll();
$ticketcount = count($tickets);
$ticketopen = 0;
foreach($tickets as $ticket){
	if($ticket['status.status'] != 'Closed') {
		if($ticket['status.status'] != 'Canceled'){
			$ticketopen++;
		}
	}
}
$content .= $framework->get('tickets')->generateListDisplay($ticket);
$content .='<h5>There are ';
$content .= $ticketcount;
$content .= ' tickets. ';
$content .= $ticketopen;
$content .= ' of which are not closed.</h5>';
?>
