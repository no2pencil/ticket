<div id="RingUrlModal" class="modal hide fade" data-invoice="<?php echo $ticket['ticket.id']; ?>" data-phone="<?php echo $ticket['customer.primaryPhone']; ?>" tabindex="-1" role="dialog" aria-labelledby="RingUrlModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="RingUrlModal">Calling <?php echo $ticket['customer.name'].' : '.$ticket['customer.primaryPhone']; ?></h3>
  </div>
  <div class="modal-body">
	&nbsp;
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary">Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>
<?php
	$framework->get('ring_central')->log_call();
?>
