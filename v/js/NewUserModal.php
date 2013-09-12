<div id="NewUserModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="NewUserModalLabel" aria-hidden="false">
  <form action="users.php" method="post" class="form-horizontal">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3 id="NewUserModalLabel">New User</h3>
  </div>
  <div class="modal-body">
    <input type="hidden" name="new" value="process">
    <fieldset>
    <legend>New user</legend>
    <div class="control-group">
      <label class="control-label" for="name">Name</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="name" name="name">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="username">Username</label>
      <div class="controls">
        <input type="text" class="input-xlarge" id="username" name="username">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="password">Password</label>
      <div class="controls">
        <input type="password" class="input-xlarge" id="password" name="password">
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="password2">Retype password</label>
      <div class="controls">
        <input type="password" class="input-xlarge" id="password2" name="password2">
      </div>
    </div>
    </fieldset>
  </div>
  <div class="modal-footer">
    <button class="btn btn-primary">Save</button>
    <button class="btn btn-warning" data-dismiss="modal" aria-hidden="true">Cancel</button>
  </div>
  </form>
</div>

