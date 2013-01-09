<div id="EditCustomerModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="EditCustomerModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="EditCustomerModalLabel">Edit <?php echo $CustomerData['customer.name']; ?></h3>
  </div>
  <div class="modal-body">
    <form action="customers.php" method="post" class="form-horizontal">
      <input type="hidden" name="update" value="true">
      <input type="hidden" name="customer_id" value="<?php echo $CustomerData['customer.id']; ?>">
      <div class="control-group">
        <label class="control-label">Name</label>
        <div class="controls">
          <input type="text" name="name" value="<?php echo $CustomerData['customer.name']; ?>">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Email</label>
        <div class="controls">
          <input type="text" name="email" value="<?php echo $CustomerData['customer.email']; ?>">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Primary phone</label>
        <div class="controls">
          <input type="text" name="primaryPhone" value="<?php echo $CustomerData['customer.primaryPhone']; ?>">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Secondary phone</label>
        <div class="controls">
          <input type="text" name="secondaryPhone" value="<?php echo $CustomerData['customer.secondaryPhone']; ?>">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Address</label>
        <div class="controls">
          <input type="text" name="address" value="<?php echo $CustomerData['customer.address']; ?>">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Referral</label>
        <!-- <select name="referral" data-placeholder="Referrals" class="chzn-select search-query span3"> -->
        <div class="controls">
          <select name="referral">
          <?php
            $Referrals = $framework->get('customers')->getReferrals();
            if($Referrals) {
              foreach($Referrals as $Referral) {
                echo '<option value="'.$Referral['reff.id'].'"';
		if($CustomerData['customer.referral']==$Referral['reff.id']) {
			echo ' selected="selected"';
		}
		echo '>'.$Referral['reff.reff'].'</option>';
              }
            }
          ?>
          </select>
        </div>
      </div>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary">Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>
