<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/model/Movie.php';
require_once '../lib/base/Session.php';
require_once '../lib/model/User.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Helper::redirect('user/login.php');
}

$movie = new Movie();

$data = $movie->find(array(
	'joins' => array(
		'movie_category' => array(
			'type' => 'INNER',
			'main_key' => 'category_id',
			'join_key' => 'id'
		)
	)
), 'all');
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
<div class="heading">Movies Management</div>
<div class="box_table">
    <div class="container">
		<table>
			<thead>
				<tr>
					<th>ID</th>
					<th>Created</th>
					<th>Title</th>
					<th>Duration</th>
					<th>Language</th>
					<th>Country</th>
					<th>Category</th>
					<th>Calendar Edit</th>
					<th>Edit</th>
					<th>Delete</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($data)) :
					$country = unserialize(M_COUNTRY);
				?>
					<?php foreach ($data as $_item) : $item = $_item['Movie']; ?>
						<tr>
							<td class="center">
								<?php echo $item['id']; ?>
							</td>
							<td>
								<?php echo date('Y/m/d H:i:s', strtotime($item['created'])); ?>
							</td>
							<td>
								<?php echo $item['title']; ?>
							</td>
							<td>
								<?php echo $item['duration']; ?> minutes
							</td>
							<td>
								<?php echo $item['language']; ?>
							</td>
							<td>
								<?php echo $country[$item['country']]; ?>
							</td>
							<td>
								<?php echo $_item['movie_category']['title']; ?>
							</td>
							<td class="center">
								<a href="calendar.php?id=<?php echo $item['id']; ?>" class="popup active">Calendar</a>
							</td>
							<td class="center">
								<a href="edit.php?id=<?php echo $item['id']; ?>" class="popup active">Edit</a>
							</td>
							<td class="center">
								<a href="delete.php?id=<?php echo $item['id']; ?>" class="popup active delete">Delete</a>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="9">
							There is no movie at the moment. <a href="create.php">Create a movie</a> now !
						</td>
					</tr>
				<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>

</body>
</html>