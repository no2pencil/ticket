<?php
/*
 * This is a complete HTML file because the template will NOT be included with it for security reasons
*/
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
		<link rel="stylesheet" href="<?php echo _SITE_ROOT; ?>/views/css/bootstrap.min.css"/>
		<style type="text/css">
	      body {
	        padding-top: 40px;
	        padding-bottom: 40px;
	        background-color: #f5f5f5;
	      }

	      .form-signin {
	        max-width: 300px;
	        padding: 19px 29px 29px;
	        margin: 0 auto 20px;
	        background-color: #fff;
	        border: 1px solid #e5e5e5;
	        -webkit-border-radius: 5px;
	           -moz-border-radius: 5px;
	                border-radius: 5px;
	        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
	                box-shadow: 0 1px 2px rgba(0,0,0,.05);
	      }
	      .form-signin .form-signin-heading,
	      .form-signin .checkbox {
	        margin-bottom: 10px;
	      }
	      .form-signin input[type="text"],
	      .form-signin input[type="password"] {
	        font-size: 16px;
	        height: auto;
	        margin-bottom: 15px;
	        padding: 7px 9px;
	      }
	    </style>

		<script src="<?php echo _SITE_ROOT; ?>/views/js/jquery-1.9.1.min.js"></script>
		<script src="<?php echo _SITE_ROOT; ?>/views/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<form class="form-signin" action="login" method="post">
				<h2 class="form-signin-heading">Please login</h2>
				<?php if(isset($view->error_msg)){ ?>
					<div class="alert alert-danger">
						<?php echo $view->error_msg; ?>
					</div>
				<?php } ?>
				<?php if(isset($view->success_msg)){ ?>
					<div class="alert alert-success">
						<?php echo $view->success_msg; ?>
					</div>
				<?php } ?>
				<input type="text" name="username" class="input-block-level" placeholder="Username or email address"/>
				<input type="password" name="password" class="input-block-level" placeholder="Password"/>
				<button class="btn btn-large btn-primary" type="submit">Login</button>
			</form>
		</div>
	</body>
</html>