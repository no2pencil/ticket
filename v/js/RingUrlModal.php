<?php
if($_GET['view']) {
  if(strpos($_SERVER['REQUEST_URI'],'tickets')) {
    $tickets[] = $framework->get('tickets')->getTicketById($_GET['view']);
    /* DEBUG INFO */
    foreach($tickets as $key => $_ticket) {
      if(isset($DEBUG)) {
        echo "<pre>";
        print_r($_ticket);
        echo "</pre>";
      }
    }
    $phone = $framework->get('utils')->formatSearchPhone($_ticket['customer.primaryPhone']);
    $call_url = $framework->get('ring_central')->make_url($_ticket['customer.primaryPhone']);
  } else {
    if(strpos($_SERVER['REQUEST_URI'],'customers')) {
      $customers[] =  $framework->get('customers')->getInfoById($_GET['view']);
      /* DEBUG INFO */
      foreach($customers as $key => $_customer) {
        if(isset($DEBUG)) {
          echo "<pre>"; 
          print_r($_customer);
          echo "</pre>";
        }
      }
      $call_url = $framework->get('ring_central')->make_url($_customer['customer.primaryPhone']);
      $phone = $framework->get('utils')->formatSearchPhone($_customer['customer.primaryPhone']);
    }
  }
  if(isset($DEBUG)) {
    echo '<b>Phone : '.$phone.'</b><br>';
    echo '<b>URL : '.$call_url;
  } 
  $hangup_url="https://service.ringcentral.com/ringout.asp?cmd=cancel&sessionid=";
}
?>
<div id="RingUrlModal" class="modal hide fade" data-invoice="<?php echo $ticket['ticket.id']; ?>" data-phone="<?php echo $ticket['customer.primaryPhone']; ?>" tabindex="-1" role="dialog" aria-labelledby="RingUrlModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="RingUrlModal">Calling <?php echo $ticket['customer.name'].' : '.$phone; ?></h3>
  </div>
  <div class="modal-body" id="body">
    <?php if($phone) { ?>
      <iframe id="frame" src="" width="50%"></iframe>
      <span id="post_response">...</span>
    <?php } ?>
  </div>
  <div class="modal-footer">
    <button id="Call" class="btn btn-success">Call</button>
    <button id="Hangup" class="btn btn-warning" disabled="">Hangup</button>
    <button class="btn btn-error" data-dismiss="modal" area-hidden="true">Close</button>
  </div>
  </form>
</div>
<script>
$(document).ready(function() {
	$("#Call").click(function() { 
		$.ajax({
			url: 'v/ring_central_call.php',
			type: 'POST',
			data: '<?php echo $call_url; ?>',
			success: function (response) {
				var temp = new Array();
				temp = response.split(" ");
				$("#body span").text(temp[1]);
			}
		});
		$("#Hangup").removeAttr("disabled");
		$("#Call").attr("disabled","disabled");
	});
	$("#Hangup").click(function() {
                $("#Hangup").attr("disabled","disabled");
		$("#Call").removeAttr("disabled");
                var session_id = $("#post_response").text();
        	var url = "<?php echo $hangup_url; ?>" + $("#post_response").text();
                alert(url);
		$("#frame").attr("src", url);
	});
});
</script>
