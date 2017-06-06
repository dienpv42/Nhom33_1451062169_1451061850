<?php 
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/model/User.php';

$user = new User();

if ($_POST) {
	$data = $_POST['data'];
	
	if ($user->login($data)) {
		if ($user->isAdmin()) {
			header('Location: ./');
		} else {
			header('Location: ../');
		}
	} else {
		$login = false;
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
<?php include '../templates/header2.php'
?>
	<div class="content">
		<div class="container">
		    <div class="col-md-1"></div>
			<div class="col-md-6">
				<div class="col-md-12">
				    <div class="product-collateral account-tabs">
				    	<ul class="toggle-tabs">
				    		<li class="left"><a href="login.php">Đăng Nhập</a></li>
				    		<li class="right"><a href="../customer/register.php">Đăng Ký</a></li>
				    	</ul>
				    </div>
				</div>
				<div class="col-md-12">	

	                <?php if (isset($login) && !$login) : ?>
	                	<p class="err">Đăng nhập không thành công! Bạn hãy kiểm tra lại email hoặc password của mình<!/p>
	                <?php endif; ?>

	                <form class="from-login-account" method="post">
	                    <div class="col-md-7">
	                    <div class="inputlogin">
	                        <ul class="form-list">
	                        	<li class="login-input-email">
	                        		<div class="input-box validation-passed">
	                        		    <?php echo $user->form->input('email'); ?>
	                        		    <?php echo $user->form->error('email'); ?>
	                        		</div>
	                        	</li>
	                        	<li class="login-input-pass">
	                        		<div class="input-box validation-passed">
	                        			<?php echo $user->form->input('password'); ?>
                                        <?php echo $user->form->error('password'); ?>
	                        		</div>
	                        	</li>
	                        </ul>	
	                    </div>
	                    </div>
	                    <div class="col-md-5">
	                    <div class="buttons-set login-button-submit">
	                        <button type="submit" name="submit" value="Login">
	                        	<span>Đăng Nhập</span>
	                        </button> 
	                    </div>  
	                    </div>	
	                </form>
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