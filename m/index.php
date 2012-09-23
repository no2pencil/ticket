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
//$content .= $framework->get('tickets')->generateListDisplay($ticket);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0;
        $data = $framework->get('tickets')->getBulkOpen(25, $page);
        $content .= '<h3>Viewing current open tickets</h3>';
        if(empty($data)){
                $content .= '
                <div class="alert alert-error">
                <strong>There are no more tickets</strong> | <a href="javascript:history.go(-1)">Go Back</a>
                </div>';
        } else {
                $content .= $framework->get('tickets')->generateListDisplay($data);
	}

$content .='<h5>There are ';
$content .= $ticketcount;
$content .= ' tickets. ';
$content .= $ticketopen;
$content .= ' of which are not closed.</h5>';
?>
