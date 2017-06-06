<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/base/Helper.php';
require_once '../lib/model/Movie.php';
require_once '../lib/model/Calendar.php';
require_once '../lib/model/User.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Helper::redirect('user/login.php');
}

$calendar = new Calendar();
$movie = new Movie();

$id = isset($_GET['id']) ? intval($_GET['id']) : null;

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

$calendarList = $calendar->find(array(
	'conditions' => array('movie_id' => $id)
));
$dataList = array();
if (!empty($calendarList)) {
	foreach ($calendarList as $item) {
		$dataList[$item['Calendar']['day']][] = $item['Calendar']['time'];
	}
}

$times = unserialize(M_TIME);
?>
<!DOCTYPE html>
<title>Movie Calendar</title>
<?php include "../templates/css.php"; ?>
<?php include "../templates/js.php"; ?>
</head>
<body>

<?php include '../templates/head.php'; ?>
<?php include '../templates/f_gnav.php'; ?>

<div class="heading">Movie Calendar</div>
<h2><?php echo $detail['Movie']['title']; ?></h2>
<br>
	<?php foreach ($dataList as $date => $time) : ?>
		<ul class="calendar_view">
			<li class="date"><?php echo $date; ?></li>
			<?php foreach ($time as $item) : ?>
				<li>
					<a href="camera_room.php?id=<?php echo $id; ?>&date=<?php echo $date; ?>&time=<?php echo $item; ?>"><?php echo $item; ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
	
<script type="text/javascript">

function delTime (obj) {
	obj.closest('li').remove();
}

function addTime(obj) {
	var date = obj.attr('data-date');

	$.ajax({
		url: 'ajax_time.php?date=' + date,
		success: function(data) {
			obj.before(data);
		}
	});
}

function addDate(obj) {
	$.ajax({
		url: 'ajax_date.php',
		success: function(data) {
			obj.before(data);
		}
	});
}

</script>
</body>
</html>