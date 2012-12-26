<?php
	require_once("head.php");
?>
<body>
	<p align=center>
		<img src="http://www.imagine-net-tech.com/images/network.jpg" width="50%">
	</p>
	<?php
		echo '<form class="box login" action="index.php" method="POST" ';
		if(isset($_GET['msg'])) echo 'style="height: 300px;"'; 
		echo '>';
		if(isset($_GET['msg'])){
		echo '<fieldset class="boxBody">
			<label>' . $_GET['msg'] . '</label></fieldset>';
		}
	?>
	<fieldset class="boxBody">
		<label>Username</label>
		<input type="text" name="username" tabindex="1" placeholder="" required>
		<label>
			<a href="#" class="rLink" tabindex="5">Forget your password?</a>Password
		</label>
		<input type="password" name="password" tabindex="2" required>
	</fieldset>
	<footer>
		<label><input type="checkbox" tabindex="3">Keep me logged in</label>
		<button class="btn btn-primary" type="submit">Login</button>
	</footer>
	</form>
	<footer id="main" class="hidden-phone">
		<div class="hidden-phone">
			<em class="icon-globe"></em><em class="icon-tasks"></em>
		</div>
	</footer>
</body>
</html>

