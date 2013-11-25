<div id="StatusesModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="StatusesModalLabel" aria-hidden="false">
  <form action="statuses.php" method="get" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="StatusesModalLabel">Status Management</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name="new" value="process">
    <fieldset>
    <legend>Statuses</legend>
    <div class="control-group">
      <ul class="nav nav-pills">
        <?php foreach($Statuses as $Status) { 
          $status_content = '<span class="badge badge-';
          $status_content .= $Status['color'].'">';
          $status_content .= '<i class="'.$Status['icon'];
          $status_content .= ' id="'.$Status['id']; 
          $status_content .= '" data-value="';
          $status_content .= $Status['text'];
          $status_content .= '"> '.$Status['text'].'</i></span>';
          $status_content .= '<span class="badge badge-invert"><a href="#" class="icon-edit"> Edit</a></span>';
          echo $status_content;
        ?>
        </li><br><br>
        <?php } ?>
      </ul>
    </div>

    <legend>New Status</legend>
    <div class="control-group">
      <label class="control-label" for="text">Status Text</label>
      <div class="controls">
        <input type="text" class="input-xlarge input-typename" id="text" name="text">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="icon">Status Icon</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="icon" name="icon">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="color">Status Color</label>
      <div class="controls">
        <select name="color" id="color">
          <option value="default">Gray</option>
          <option value="primary">Blue</option>
          <option value="info">Light-Blue</option>
          <option value="success">Green</option>
          <option value="warning">Yellow</option>
          <option value="error">Red</option>
          <option value="inverse">Black</option>
        </select>
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
