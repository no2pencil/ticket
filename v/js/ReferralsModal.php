<div id="ReferralsModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="ReferralsModalLabel" aria-hidden="false">
  <form action="" method="post" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="ReferralsModalLabel">Referrals Management</h3>
  </div>
  <div class="modal-body">
    <div id='body'>
      <i class="icon-spinner icon-spin icon-large"></i> aving...
    </div>
    <fieldset>
    <table id='referrals_management' class="table table-striped">
      <tbody>
        <tr><td>#</td><td>Referral</td></tr>
        <?php
          $referrals = $framework->get('customers')->getReferrals();
          foreach($referrals as $referral) {
            echo '<tr><td>'.$referral['reff.id'].'</td><td>'.$referral['reff.reff'].'</td></tr>';
          }
        ?>
        <tr><td colspan=2 id='add'><button>Add</button></td></tr>
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
<script>
  $('#add').click(function (e) {
    e.preventDefault();
    $('#add').hide(500);
    $('#referrals_management').append('<tr><td>&nbsp;</td><td><input type="text" name=""></td></tr>');
  });

  $('#save').click(function (e) {
    e.preventDefault();
    $.ajax({
      url: 'v/ReferralsEngine.php',
      type: 'POST',
      data: 'test',
      success: function (response) {
        var temp = new Array();
        temp = response.split(" ");
        $("#body span").text(temp[1]);
      }
      /*error: function (response) {
        var temp = 'Something went horribly wrong!';
      }*/
    });
  });
</script>
