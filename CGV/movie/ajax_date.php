<?php
require_once '../lib/config/const.php';
require_once '../lib/config/database.php';
require_once '../lib/base/Helper.php';

$times = unserialize(M_TIME);

$today = date('Y-m-d');
?>
<ul class="calendar_date">
	<li class="date"><input type="text" class="datepicker date_add" name="date_add" value="<?php echo $today; ?>" /></li>
	<li>
		<select name="times[<?php echo $today; ?>][]" class="times">
			<?php foreach ($times as $_time) : ?>
				<option value="<?php echo $_time; ?>"><?php echo $_time; ?></option>
			<?php endforeach; ?>
		</select> <a href="javascript:;" class="del_time" onclick="delTime($(this));"><img src="../images/delete.png" /></a>
	</li>
	<a href="javascript:;" class="add_time" data-date="<?php echo $today; ?>" onclick="addTime($(this));"><img src="../images/add.png" /></a>
</ul>

<script type="text/javascript">
$(function() {
	$(".datepicker").datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date() });

	$(".datepicker").change(function() {
		var date = $(this).val();
		var parent = $(this).closest('.calendar_date');
		parent.find('.add_time').attr('data-date', date);

		var select = parent.find('select');
		select.each(function() {
			$(this).attr('name', 'times[' + date + '][]');
		});
	});
});
</script>