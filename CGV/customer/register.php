<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/model/User.php';

$user = new User();

if ($_POST) {
	$data = $_POST['data'];
	$data['User']['created'] = date('Y-m-d H:i:s');
	$data['User']['modified'] = date('Y-m-d H:i:s');
	
	if ($user->saveLogin($data)) {
		Helper::redirect('movie');
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Document</title>
	<?php 
	include '../templates/css2.php';
	include '../templates/js.php';
	 ?>
	<link rel="stylesheet" href="../css/register.css">

</head>
<body>
<?php include '../templates/header2.php'
?>
	<div class="content">
		<div class="container">
		    <div class="col-md-1"></div>
			<div class="col-md-6">
				<div class="col-md-12">
				    <div class="product-collateral account-tabs">
				    	<ul class="toggle-tabs">
				    		<li class="left"><a href="../user/login.php">Đăng Nhập</a></li>
				    		<li class="right"><a href="register.php">Đăng Ký</a></li>
				    	</ul>
				    </div>
				</div>
				<div class="col-md-12">	
				    <div class="formdk">
                    <form action="" class="form" method="post">
                        <ul>
                            <li>
                    			<label class="required">
                    				Email
                    			</label>
                    			<div>
                    				<?php echo $user->form->input('email'); ?>
                    				<?php echo $user->form->error('email'); ?>
                    			</div>
                    		</li>
                    		<li>
                    			<label class="required">
	                    			Password
	                    		</label>
	                    		<div>
	                    			<?php echo $user->form->input('password'); ?>
	                    			<?php echo $user->form->error('password'); ?>
	                    		</div>
	                    	</li>
	                    	<li>
	                    		<label class="required">
	                    			Fullname
	                    		</label>
	                    		<div>
	                    			<?php echo $user->form->input('fullname'); ?>
	                    			<?php echo $user->form->error('fullname'); ?>
	                    		</div>
                            </li>
                            <li>
	                    		<label class="required">
	                    			Address
	                    		</label>
	                    		<div>
	                    			<?php echo $user->form->input('address'); ?>
	                    			<?php echo $user->form->error('address'); ?>
	                    		</div>
                            </li>
	                    </ul>
	                    <div>
	                    	<input class="button" type="submit" name="submit" value="Register">
	                    </div>
                    </form>
                    </div>
				</div>
				<div class="col-md-12">
				    <div class="product-collateral account-tabs">
				        <ul class="toggle-tabs"></ul>
				    </div>
		
				</div>
			</div>
			<div class="col-md-5"></div>
		</div>
	</div>
</body>
</html>