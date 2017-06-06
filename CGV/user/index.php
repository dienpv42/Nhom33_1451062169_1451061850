<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/model/User.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Helper::redirect('user/login.php');
}

$data = $user->findAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<?php 
	include '../templates/css2.php';
	include '../templates/js2.php';
	 ?>
</head>
<body>
<?php include '../templates/gnav.php'
?>
<div class="heading">User Management</div>
<div class="box_table">
	<div class="container">  
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Email</th>
					<th>Full name</th>
					<th>Address</th>
					<th>Created</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php foreach ($data as $item) : $item = $item['User']; ?>
						<tr>
							<td>
								<?php echo $item['id']; ?>
							</td>
							<td>
								<?php echo $item['email']; ?>
							</td>
							<td>
								<?php echo $item['fullname']; ?>
							</td>
							<td>
								<?php echo $item['address']; ?>
							</td>
							<td class="center">
								<?php echo date('Y/m/d H:i:s', strtotime($item['created'])); ?>
							</td>						
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

	
</body>
</html>