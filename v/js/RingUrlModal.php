<?php
$tickets[] = $framework->get('tickets')->getTicketById($_GET['view']);
$phone = $framework->get('utils')->formatSearchPhone($customer['customer.primaryPhone']);
/* DEBUG INFO */
/*
foreach($tickets as $key => $ticket) {
  echo "<pre>";
    print_r($ticket);
  echo "</pre>";
}
*/
$call_url = $framework->get('ring_central')->make_url($customer['customer.primaryPhone']);
?>
<div id="RingUrlModal" class="modal hide fade" data-invoice="<?php echo $ticket['ticket.id']; ?>" data-phone="<?php echo $ticket['customer.primaryPhone']; ?>" tabindex="-1" role="dialog" aria-labelledby="RingUrlModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="RingUrlModal">Calling <?php echo $ticket['customer.name'].' : '.$phone; ?></h3>
  </div>
  <div class="modal-body" id="body">
    <?php if($customer['customer.primaryPhone']) { ?>
      <iframe id="frame" src="" width="50%"></iframe>
    <?php } ?>
  </div>
  <div class="modal-footer">
    <button id="Call" class="btn btn-success">Call</button>
    <button id="Hangup" class="btn btn-warning">Hangup</button>
    <button class="btn btn-error" data-dismiss="modal" area-hidden="true">Close</button>
  </div>
  </form>
</div>
<script>
$("#Call").click(function () { 
	$("#frame").attr("src", "<?php echo $call_url; ?>");
});
$("#Hangup").click(function () {
	$("#frame").attr("src", "<?php echo $hangup_url; ?>");
});
</script>
