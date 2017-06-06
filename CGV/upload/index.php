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
	<div class="heading">Upload Management</div>
	<div class="box_table">
		<div class="container">
			<form class="upload" action="" method="post" enctype="multipart/form-data">
				<input type="file" class="button" name="up-file"><br>
				<input type="submit" class="button" value="Upload">
			</form>
		</div>
	</div>
</body>
</html>