<?php
	require_once("head.php");
?>
<body id="main">
  <div class="row">
    <div class="span4 offset4">
      <div class="well">
        <legend>Sign in to Ticket System</legend>
        <form method="POST" action="index.php" accept-charset="UTF-8">
          <?php if(isset($_GET['msg'])) { ?>
          <div class="alert alert-error">
            <a class="close" data-dismiss="alert" href="#">x</a><?php echo $_GET['msg']; ?>
          </div>
          <?php } ?>
          <input class="span3" placeholder="Username" type="text" name="username">
          <input class="span3" placeholder="Password" type="password" name="password"> 
          <label class="checkbox">
            <input type="checkbox" name="remember" value="1"> Remember Me
          </label>
          <button class="btn-info btn" type="submit">Login</button>      
        </form>    
      </div>
    </div>
  </div>
  <footer id="main" class="hidden-phone">
    <div class="hidden-phone">
      <em class="icon-globe"></em><em class="icon-tasks"></em>
    </div>
  </footer>
</body>
</html>

