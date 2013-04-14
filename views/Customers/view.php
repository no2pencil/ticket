<h1>Customers / View</h1>
<h2>Viewing <a href="#"><?php echo $view->name; ?></a>'s account</h2>
<table class="table table-bordered">
	<tbody>
		<tr><td class="span2">Email</td><td><?php echo $view->email; ?></td></tr>
		<tr><td>Phone</td><td><?php echo $view->phone; ?></td></tr>
		<tr><td>Address</td><td><?php echo $view->address; ?></td></tr>
	</tbody>
</table>