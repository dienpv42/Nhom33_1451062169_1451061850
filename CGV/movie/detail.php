<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/base/Helper.php';
require_once '../lib/model/Movie.php';
require_once '../lib/model/User.php';

$user = new User();

if (!$user->isLoggedIn()) {
	Helper::redirect('user/login.php');
}
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

?>
<!DOCTYPE html>
<title>Movie Detail</title>
<?php include "../templates/css.php"; ?>
<?php include "../templates/js.php"; ?>
</head>
<body>

<?php include '../templates/head.php'; ?>
<?php include '../templates/f_gnav.php'; ?>

<ul class="con_movie">
<?php if (!empty($detail)) :
	$country = unserialize(M_COUNTRY);
?>
<?php $item = $detail['Movie']; ?>
	<li class="box_movie">
		<div class="detail_l">
			<p>
				<a href="detail.php?id=<?php echo $item['id']; ?>">
					<img alt="" src="http://www.hotelkeihan.co.jp/admin/img/imagecache/510x359_479ab07d1791ff47b2cd7e8df18cb643.jpg">
				</a>
			</p>
		</div>
		<div class="detail_r">
			<p class="title"><a href="detail.php?id=<?php echo $item['id']; ?>"><?php echo $item['title']; ?></a></p>
			<div class="txt">
				<p class="info"><a href="calendar_view.php?id=<?php echo $item['id']; ?>"><img src="../images/send.png" /> Đặt vé</a></p>
				<p class="info"><span>Khởi chiếu:</span> <?php echo date('Y-m-d', strtotime($item['open_date'])); ?></p>
				<p class="info"><span>Đạo diễn:</span> <?php echo $item['director']; ?></p>
				<p class="info"><span>Diễn viên:</span> <?php echo $item['actor']; ?></p>
				<p class="info"><span>Thời lượng:</span> <?php echo $item['duration']; ?> phút</p>
				<p class="info"><span>Thể loại:</span> <?php echo $detail['movie_category']['title']; ?></p>
				<p class="info"><?php echo $item['description']; ?></p>
			</div>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $item['trial_url']; ?>" frameborder="0" allowfullscreen></iframe>
		</div>
	</li>
	
<?php endif; ?>
</ul>

</body>
</html>