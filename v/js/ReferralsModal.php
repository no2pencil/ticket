<div id="ReferralsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ReferralsModalLabel" aria-hidden="false">
  <form action="" method="post" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="ReferralsModalLabel">Referrals Management</h3>
  </div>
  <div class="modal-body">
    <fieldset>
    <table class="table table-striped">
    <tr><td>#</td><td>Referral</td></tr>
    <?php
      $referrals = $framework->get('customers')->getReferrals();
      foreach($referrals as $referral) {
        echo '<tr><td>'.$referral['reff.id'].'</td><td>'.$referral['reff.reff'].'</td></tr>';
      }
    ?>
    </table>
    </fieldset>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="new" value="process">
    <button class="btn btn-primary">Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>

