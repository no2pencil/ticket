<?php

	if(!$framework->get('ring_central')->get_creds()) {
		echo "<h2>Ring Central</h2>";
	}

	if(!isset($Statuses)) {
		$Statuses = $framework->get('status')->getStatuses();
		$StatusTypes = $framework->get('status')->getTypes();
	}
         
	if(!isset($Repairs)) {
		$Repairs = $framework->get('tickets')->getRepairs();
	}


        $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 0;
	if($_POST['filter']) {
		$repair_id = 0;
		$status_id = 0;
		$repair_id = strip_tags($_POST[repair]);
		$status_id = strip_tags($_POST[status]);
		$data = $framework->get('tickets')->getFilter(25, $page, $repair_id, $status_id);
		$view_text = "Viewing selected tickets";
	} else {
        	$data = $framework->get('tickets')->getBulkOpen(25, $page);
		$view_text = "Viewing current open tickets";
	}
        $content .= '<h3>'.$view_text.' : ';
	$content .= '<form name="filter" id="filter" method="POST">';
	$content .= '<input type="hidden" name="filter" value="true">';
	$content .= '<select name="repair"><option value=0>All</option>';
	foreach($Repairs as $key => $value) {
		$content .= '<option value='.$value[id];
		if($repair_id == $value[id]) $content .= ' selected';
		$content .= '>'.$value[description].'</option>';
	}
	$content .= '</select>&nbsp; ';

        $content .= '<select name="status"><option value=0>All</option>';
        foreach($Statuses as $key => $value) {
		// Not sure WHY this is, this really needs to be troubleshot & figured out!
		if($value[description] == 12) {
                	$content .= '<option value='.$value[id];
			if($status_id == $value[id]) $content .= ' selected';
			$content .= '>'.$value[status].'</option>';
		}
        }
        $content .= '</select>&nbsp;';
	$content .= '<button type="submit" class="btn btn-default"><i class="icon-white icon-fire"></i> Filter Tickets</button></form>';
	$content .= '</h3>';

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
