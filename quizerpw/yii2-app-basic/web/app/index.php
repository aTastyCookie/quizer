<?php
	
include 'lib/Snoopy.class.php';

$snoopy = new Snoopy;
	
$snoopy->fetch("http://sevastopol.gov.ru/feedback/new/");

$html = $snoopy->results;

preg_match_all("'src=\"\/bitrix\/tools\/captcha\.php\?captcha_sid=(.*?)\"'ims", $html, $p);
$sid = $p[1][0];

echo $sid . ' <br> <img src="http://sevastopol.gov.ru/bitrix/tools/captcha.php?captcha_sid=' . $sid . '">';

?>

<form action="">
	<input type="text" size="60" name="code" value="">
	<br/>
	<input type="submit" value="go" />
</form>