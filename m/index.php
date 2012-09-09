<?php
$tickets = $framework->get('tickets')->getAll();
$ticketcount = count($tickets);
$ticketopen = 0;
foreach($tickets as $ticket){
	if($ticket['status.status'] != 'closed'){
		$ticketopen++;
	}
}
$content = '<h2>Home</h2>
	<h4>There are ' . $ticketcount . ' tickets. ' . $ticketopen . ' of which are not closed';
?>