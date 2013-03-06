<div id="StatusesModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="StatusesModalLabel" aria-hidden="false">
  <form action="statuses.php" method="get" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="StatusesModalLabel">Status Management</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name="new" value="process">
    <fieldset>
    <legend>Status Type</legend>
    <div class="control-group">
      <ul class="nav nav-pills">
        <?php foreach($StatusTypes as $Type) { ?>
        <li class="dropdown">
          <a class="dropdown-toggle input-class-typename" id="<?php echo $Type['id']; ?>" data-toggle="dropdown" href="#" data-value="<?php echo $Type['name']; ?>">
            <?php echo $Type['name']; ?>
            <b class="caret"></b>
          </a>
          <ul class="dropdown-menu">
            <?php
              foreach($Statuses as $Status) {
                if(isset($Status)) {
                  if($Type['id'] == $Status['description']) {
                    echo '<li><a href="#">'.$Status['status'].'</a></li>';
                  }
                }
              }
            ?>
          </ul>
        </li><br><br>
        <?php } ?>
      </ul>
    </div>

    <legend>New Status</legend>
    <div class="control-group">
      <label class="control-label" for="typename">Status Type</label>
      <div class="controls">
        <input type="text" class="input-xlarge input-typename" id="typename" name="typename">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="status">Status Name</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="status" name="status">
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary">Save</button>
      <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
    </div>
    </form>
  </div>
</div>
<script>
jQuery(function () {
  $('.input-class-typename').click(function () {
    var typename = $(this).text().trim();
    $('.input-typename').val(typename);
  });
});
</script>
