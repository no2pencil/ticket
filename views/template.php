<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Ticket</title>
		<link rel="stylesheet" href="<?php echo _SITE_ROOT; ?>/views/css/bootstrap.min.css"/>
		<script src="<?php echo _SITE_ROOT; ?>/views/js/jquery-1.9.1.min.js"></script>		
		<script src="<?php echo _SITE_ROOT; ?>/views/js/bootstrap.js"></script>
		<script src="<?php echo _SITE_ROOT; ?>/views/js/scripts.js"></script>

		<style>
		body {
			padding-top: 60px;
		}
		</style>
	</head>
	<body>
		<div class="navbar navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<a class="brand" href="#">Ticket</a>
					<ul class="nav">
						<?php /* TODO: dynamic links n stuff */ ?>
						<li class="active"><a href="#"><i class="icon-white icon-home"></i>Home</a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-tags"></i>
								Tickets
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="#">My tickets</a></li>
								<li><a href="#">All tickets</a></li>
								<li class="divider"></li>
								<li><a href="#">Advanced search</a></li>
							</ul>
						</li>
						<li>
							<a href="#">
								<i class="icon-user"></i>
								Customers
							</a>
						</li>
						<li>
							<a href="#">
								<i class="icon-tasks"></i>
								Reports
							</a>
						</li>
					</ul>
					<ul class="nav pull-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="icon-cog"></i>
								Administration
								<b class="caret"></b>
							</a>
							<ul class="dropdown-menu">
								<li><a href="<?php echo _SITE_ROOT; ?>/Admin/users/">Users</a></li>
								<li><a href="<?php echo _SITE_ROOT; ?>/Admin/settings/">Settings</a></li>
							</ul>
						</li>
						<li>
							<a href="<?php echo _SITE_ROOT; ?>/User/logout/">
								<i class="icon-off"></i>
								Logout
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container">
			<?php echo $view->_contents; ?>
		</div>
	</body>
</html>