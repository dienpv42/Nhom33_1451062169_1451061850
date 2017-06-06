<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/base/Helper.php';
require_once '../lib/model/Movie.php';
require_once '../lib/model/Calendar.php';
require_once '../lib/model/Booking.php';
require_once '../lib/model/User.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Helper::redirect('user/login.php');
}

$booking = new Booking();
$calendar = new Calendar();
$movie = new Movie();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;
$date = isset($_GET['date']) ? $_GET['date'] : null;
$time = isset($_GET['time']) ? $_GET['time'] : null;

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

$rows = array('B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');

$bookList = $booking->find(array(
	'conditions' => array(
		'movie_id' => $id,
		'day' => $date,
		'time' => $time
	)
));
$bookedList = array();
if (!empty($bookList)) {
	foreach ($bookList as $item) {
		$seat = explode(',', $item['Booking']['seat']);
		foreach ($seat as $_seat) {
			$bookedList[$_seat] = $item['Booking']['code'];
		}
	}
}
?>
<!DOCTYPE html>
<title>Movie Calendar</title>
<?php include "../templates/css.php"; ?>
<?php include "../templates/js.php"; ?>
</head>
<body>

<?php include '../templates/head.php'; ?>
<?php include '../templates/f_gnav.php'; ?>

<div class="heading">Movie Booking</div>
<h2><?php echo $detail['Movie']['title']; ?></h2>

<div class="desc">
	<p>Please select your seats for this movie</p>
	<p>
		Date: <b><?php echo $date; ?></b>
	</p>
	<p>
		Time: <b><?php echo $time; ?></b>
	</p>
</div>

<div class="board">
	<div class="screen">Màn hình</div>
	
	<ul class="chairs">
		<?php foreach ($rows as $row) : ?>
			<li><?php echo $row; ?></li>
		<?php endforeach; ?>
	</ul>
	<table class="seats">
		<?php foreach ($rows as $row) : ?>
			<tr>
				<?php for ($seat = 1;$seat <= 16;$seat++) : $dataSeat = $row.'_'.sprintf('%02d', $seat); ?>
					<td data-seat="<?php echo $dataSeat; ?>" <?php echo isset($bookedList[$dataSeat]) ? 'class="booked"' : ''; ?>><?php echo sprintf('%02d', $seat); ?></td>
					<?php if ($seat == 8) : ?>
						<td class="empty">&nbsp;</td>
						<td class="empty">&nbsp;</td>
						<td class="empty">&nbsp;</td>
						<td class="empty">&nbsp;</td>
					<?php endif; ?>
				<?php endfor; ?>
			</tr>
		<?php endforeach; ?>
	</table>
	
	<form action='order.php?id=<?php echo $id; ?>&date=<?php echo $date; ?>&time=<?php echo $time; ?>' method="POST">
		<input type="hidden" name="seats" id="seats"/>
	</form>
	
	<div class="btn">
		<a href="javascript:;" class="btn_cancel">Cancel</a>
		<a href="javascript:;" class="btn_buy">Continue</a>
	</div>
</div>

<script type="text/javascript">
$(function() {
	$('.seats td').click(function() {
		var cls = $(this).attr('class');

		if (cls == 'empty' || cls == 'booked') {
			return;
		}

		$(this).toggleClass('selected');
	});

	$('.btn_buy').click(function() {
		var seats = [];
		var idx = 0;
		$('.seats td').each(function() {
			if ($(this).attr('class') == 'selected') {
				seats[idx] = $(this).attr('data-seat');
				idx++;
			}
		});

		$('#seats').val(seats.join(','));
		
		$('form').submit();
	});
	
	$('.btn_cancel').click(function() {
		window.location = 'list.php';
	});
});
</script>
</body>
</html>