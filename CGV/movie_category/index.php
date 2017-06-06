<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/model/MovieCategory.php';
require_once '../lib/model/User.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Helper::redirect('user/login.php');
}

$mCategory = new MovieCategory();

$data = $mCategory->findAll();
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
<div class="heading">Movie Categories Management</div>
<div class="box_table">
	<div class="container">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Title</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) : ?>
					<?php foreach ($data as $item) : $item = $item['MovieCategory']; ?>
						<tr>
							<td class="center">
								<?php echo $item['id']; ?>
							</td>
							<td>
								<?php echo $item['title']; ?>
							</td>
							<td class="center">
								<a href="edit.php?id=<?php echo $item['id']; ?>" class="popup active">Edit</a>
							</td>
							<td class="center">
								<a href="delete.php?id=<?php echo $item['id']; ?>" class="popup active">Delete</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="9">
							There is no movie category at the moment. <a href="create.php">Create a movie category</a> now !
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

</body>
</html>