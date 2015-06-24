<?php
if (!$view['success']) {
	echo $sid . '/' . $sessId .' <br> <img src="http://sevastopol.gov.ru/bitrix/tools/captcha.php?captcha_sid=' . $sid . '">';
	?>
	<form action="" method="post">
		<input type="text" size="60" name="code" value="">
		<br/>
		<input type="submit" value="go" />
	</form>
	<?
}else {
	echo 'SUCCESS';
}