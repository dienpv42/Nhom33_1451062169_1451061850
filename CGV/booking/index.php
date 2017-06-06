<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/model/Booking.php';
require_once '../lib/model/User.php';


$user = new User();

if (!$user->isLoggedIn()) {
	Helper::redirect('user/login.php');
}

$booking = new Booking();

$data = $booking->findAll();
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
<div class="heading">Booking Management</div>
<div class="box_table">
	<div class="container">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Day</th>
					<th>Time</th>
					<th>Seat</th>
					<th>Code</th>
					<th>Created</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php foreach ($data as $item) : $item = $item['Booking']; ?>
						<tr>
							<td class="center">
								<?php echo $item['id']; ?>
							</td>
							<td>
								<?php echo $item['day']; ?>
							</td>
							<td>
								<?php echo $item['time']; ?>
							</td>
							<td>
								<?php echo $item['seat']; ?>
							</td>
							<td>
								<?php echo $item['code']; ?>
							</td>	
							<td>
								<?php echo $item['created']; ?>
							</td>													
							<td class="center">
								<a href="delete.php?id=<?php echo $item['id']; ?>" class="popup active">Delete</a>
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