<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/base/Helper.php';

$times = unserialize(M_TIME);

$date = isset($_GET['date']) ? $_GET['date'] : null;
?>
<li>
	<select name="times[<?php echo $date; ?>][]" class="times">
		<?php foreach ($times as $_time) : ?>
			<option value="<?php echo $_time; ?>"><?php echo $_time; ?></option>
		<?php endforeach; ?>
	</select> <a href="javascript:;" class="del_time" onclick="delTime($(this));"><img src="../images/delete.png" /></a>
</li>