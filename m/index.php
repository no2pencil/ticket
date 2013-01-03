<?php
$content .= '<h2>Home</h2>';

/* There should be a function to gather this number, not in the file */
/*
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
*/

//$content .= $framework->get('tickets')->generateListDisplay($ticket);
        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0;
        $data = $framework->get('tickets')->getBulkOpen(25, $page);
        $content .= '<h3>Viewing current open tickets</h3>';

        if($page<1){
                $previousBtn = '<li class="disabled">
                <a href="#">Previous</a>
                </li>';
        } else {
                $previousBtn = '<li>
                <a href="index.php?&page=' . ($page-1) . '">Previous</a>
                </li>';
        }

        if(empty($data)){
		$nextBtn = '<li class="disabled">
			<a href="#">Next</a>
		</li>';
                $content .= '
                <div class="alert alert-error">
                <strong>There are no more tickets</strong> | <a href="javascript:history.go(-1)">Go Back</a>
		'.$nextBtn.'<div>';
        } else {
                $content .= $framework->get('tickets')->generateListDisplay($data);
		$nextBtn = '<li>
			<a href="index.php?&page=' . ($page+1) . '">Next</a>
		</li>';
	}

        $content .= '                   
                <div class="well">
                        <ul class="pager">';
        $content .= $previousBtn . $nextBtn;
	$content .= '
                        </ul>
                </div>';

/*
$content .='<h5>There are ';
$content .= $ticketcount;
$content .= ' tickets. ';
$content .= $ticketopen;
$content .= ' of which are not closed.</h5>';
*/
