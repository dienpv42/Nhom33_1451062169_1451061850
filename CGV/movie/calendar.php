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

if ($_POST) {
	$times = isset($_POST['times']) ? $_POST['times'] : null;
	if (!empty($times)) {
		foreach ($times as $date => $time) {
			// Delete all date of this movie
			$calendar->delete(array(
				'movie_id' => $id,
				'day' => $date
			));
			
			foreach ($time as $_time) {
				// Save to calendar
				$data = array(
					'Calendar' => array(
						'movie_id' => $id,
						'day' => $date,
						'time' => $_time,
						'created' => date('Y-m-d H:i:s')
					)
				);
				$calendar->save($data);
			}
		}
		header('Location: ./');
	}
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

<div class="heading">Calendar Management</div>
<h2><?php echo $detail['Movie']['title']; ?></h2>
<br>
<form action="" class="form" method="post">
	<section><input type="submit" name="submit" value="Update Calendar" class="btn btn-green"/></section>

	<?php foreach ($dataList as $date => $time) : ?>
		<ul class="calendar_date">
			<li class="date"><?php echo $date; ?></li>
			<?php foreach ($time as $item) : ?>
				<li>
					<select name="times[<?php echo $date; ?>][]" class="times">
						<?php foreach ($times as $_time) : ?>
							<option value="<?php echo $_time; ?>" <?php echo $_time == $item ? 'selected' : ''; ?>><?php echo $_time; ?></option>
						<?php endforeach; ?>
					</select> <a href="javascript:;" class="del_time" onclick="delTime($(this));"><img src="../images/delete.png" /></a>
				</li>
			<?php endforeach; ?>
			<a href="javascript:;" class="add_time" data-date="<?php echo $date; ?>" onclick="addTime($(this));"><img src="../images/add.png" /></a>
		</ul>
	<?php endforeach; ?>
	<a href="javascript:;" class="btn_add add_date" onclick="addDate($(this));">Add Date</a>
</form>

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