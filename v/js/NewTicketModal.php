<div id="NewTicketModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="NewTicketModalLabel" aria-hidden
="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="NewTicketModalLabel">New Ticket for <?php if(isset($CustomerData['customer.name'])) echo $CustomerData['customer.name']; ?></h3>
  </div>
  <div class="modal-body">
    <form action="tickets.php" method="post" class="form-horizontal">
    <fieldset>
    <!-- Span6 has reported IE7 issues -->
    <div class="control-group">
      <label class="control-label" for="name">Ticket Type</label>
      <!-- <select name="type" data-placeholder="Ticket Types" class="chzn-select search-query span3"> -->
      <div class="controls">
        <select name="type">
          <?php
           foreach($StatusTypes as $Type) {
             echo '<option value='.$Type['id'].'>'.$Type['name'].'</option>';
           }
         ?>
       </select>
    </div>
    <div class="control-group">
       <label class="control-label" for="textarea">Comment:</label>
       <div class="controls">
         <textarea class="span6 input-xlarge" id="comment" name="comment" rows="6"></textarea>
       </div>
    </div>
    <input type="hidden" name="new" value="new">
    <input type="hidden" name="customer_id" value="<?php if(isset($CustomerData['customer.id'])) echo $CustomerData['customer.id']; ?>">
    </fieldset>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary">Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>
