<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/model/MovieCategory.php';
require_once '../lib/model/User.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Helper::redirect('user/login.php');
}

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (empty($id)) {
	Helper::redirect('movie_category');
}

$movieCategory = new MovieCategory();
$movieCategory->findById($id);
$success = false;

if ($_POST) {
	$data = $_POST['data'];
	$data['MovieCategory']['created'] = date('Y-m-d H:i:s');
	$data['MovieCategory']['modified'] = date('Y-m-d H:i:s');
	
	if ($movieCategory->save($data)) {
		header('Location: index.php');
	}
}
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

<div class="heading">Movie Category Create</div>
<div class="col-md-12">
	<div class="container">
		<div class="form-center">
			<form action="" class="form" method="post">
				<?php echo $movieCategory->form->input('id'); ?>
				<section>
					<dl>
						<dt>
							Title
						</dt>
						<dd>
							<?php echo $movieCategory->form->input('title'); ?>
							<?php echo $movieCategory->form->error('title'); ?>
						</dd>
					</dl>
				</section>
				<section>
					<dl>
						<dd>
							<input type="submit" name="submit" value="Save" class="button">
						</dd>
					</dl>
				</section>
			</form>
		</div>
	</div>
</div>
</body>
</html>