<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/base/Helper.php';
require_once '../lib/model/Movie.php';
require_once '../lib/model/Calendar.php';

$calendar = new Calendar();
$movie = new Movie();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$date = isset($_GET['date']) ? $_GET['date'] : null;
$time = isset($_GET['time']) ? $_GET['time'] : null;
$seats = isset($_POST['seats']) ? $_POST['seats'] : null;

if (empty($id)) {
	Helper::redirect('movie');
}

$detail = $movie->find(array(
	'conditions' => array('Movie.id' => $id),
	'joins' => array(
		'movie_category' => array(
			'type' => 'INNER',
			'main_key' => 'category_id',
			'join_key' => 'id'
		)
	)
), 'first');
?>
<!DOCTYPE html>
<title>Movie Calendar</title>
<?php include "../templates/css.php"; ?>
<?php include "../templates/js.php"; ?>
</head>
<body>

<?php include '../templates/head.php'; ?>
<?php include '../templates/f_gnav.php'; ?>

<div class="heading">Movie Order</div>
<h2><?php echo $detail['Movie']['title']; ?></h2>

<div class="desc">
	<p>Please be careful to check the information below before place this order</p>
	<p>
		Date: <b><?php echo $date; ?></b>
	</p>
	<p>
		Time: <b><?php echo $time; ?></b>
	</p>
	<p>
		Seats: <b>
		<?php
			$tmpSeat = explode(',', $seats);
			foreach ($tmpSeat as &$s) {
				$s = str_replace('_', '', $s);
			}
			echo implode(', ', $tmpSeat);
		?></b>
	</p>
</div>

<div class="message" style="display: none;">
	Thank you for ordering this movie. Please bring the code below to take the tickets.<br><br>
	<div class="buy_code"></div>
</div>

<div class="board">
	<div class="btn">
		<a href="javascript:;" class="btn_cancel">Cancel</a>
		<a href="javascript:;" class="btn_buy">Order</a>
	</div>
</div>

<script type="text/javascript">
$(function() {
	$('.btn_buy').click(function() {
		$.ajax({
			url: 'create_order.php?id=<?php echo $id; ?>&date=<?php echo $date; ?>&time=<?php echo $time; ?>&seats=<?php echo $seats; ?>',
			type: 'POST',
			success: function(data) {
				$('.buy_code').html(data);

				$('.message').show();
				$('.board').remove();
			}
		});
	});
	
	$('.btn_cancel').click(function() {
		window.location = 'list.php';
	});
});
</script>

</body>
</html>