<div id="ReferralsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ReferralsModalLabel" aria-hidden="false">
  <form action="" method="post" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="ReferralsModalLabel">Referrals Management</h3>
  </div>
  <div class="modal-body">
    <div id='body'>
      <i class="icon-spinner icon-spin icon-large"></i> Saving...
    </div>
    <fieldset>
    <table class="table table-striped">
      <tbody>
        <tr><td>#</td><td>Referral</td></tr>
        <?php
          $referrals = $framework->get('customers')->getReferrals();
          foreach($referrals as $referral) {
            echo '<tr><td>'.$referral['reff.id'].'</td><td>'.$referral['reff.reff'].'</td></tr>';
          }
        ?>
        <tr><td colspan=2 id='referrals_management'>&nbsp;</td></tr>
	<tr><td colspan=2>
          <input type='button' id='add-referral' value='Add'></button>
        </td></tr>
      </tbody>
    </table>
    </fieldset>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="new" value="process">
    <button class="btn btn-primary" id='save'>Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>
