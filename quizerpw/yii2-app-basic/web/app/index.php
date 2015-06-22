<?php
	
include 'lib/Snoopy.class.php';

$snoopy = new Snoopy;

if ($_POST['code']) {
	
	$sessId = file_get_contents('/tmp/app_sess_id');
	$captchaSid = file_get_contents('/tmp/app_captcha_sid');
	
	$form = array();
	
	$form['form_text_3'] = 'Дворы';
	$form['form_text_6'] = 't1x';
	$form['form_textarea_5'] = 't2';
	$form['form_email_4'] = 'ifourspb@gmail.com';
	$form['form_textarea_2'] = 't4';
	$form['captcha_word'] = $_POST['code'];
	$form['captcha_sid'] = $captchaSid;
	$form['web_form_submit'] = 'Готово';

	$html = sendRequest( $sessId, $form );
	var_dump( $html ); exit;

}else {

	$html = getPage("http://sevastopol.gov.ru/feedback/new/");

	preg_match_all("'src=\"\/bitrix\/tools\/captcha\.php\?captcha_sid=(.*?)\"'ims", $html, $p);
	$sid = $p[1][0];
	file_put_contents('/tmp/app_captcha_sid', $sid);


	preg_match_all("'\'bitrix_sessid\':\'(.*?)\''ims", $html, $p);
	$sessId = $p[1][0];	
	file_put_contents('/tmp/app_sess_id', $sessId);

	echo $sid . '/' . $sessId .' <br> <img src="http://sevastopol.gov.ru/bitrix/tools/captcha.php?captcha_sid=' . $sid . '">';

	
}

?>

<form action="" method="post">
	<input type="text" size="60" name="code" value="">
	<br/>
	<input type="submit" value="go" />
</form>

<?php

function sendRequest( $sessId, $form ) {
	$post = array();
	$post['sessid'] = $sessId;
	$post['WEB_FORM_ID'] = '1';
	foreach ($form as $k => $v) $post[$k] = $v;

	$postvars = '';
	
	foreach($post as $key=>$value) {
		$postvars .= $key . "=" . $value . "&";
	}
	
	$url = "http://sevastopol.gov.ru/feedback/new/index.php";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL ,$url);
	curl_setopt($ch, CURLOPT_POST, 1);                //0 for a get request
	curl_setopt($ch, CURLOPT_POSTFIELDS,$postvars);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");
	curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/cookie.txt");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	$errNo = curl_errno($ch);
	$requestContentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
	$response = curl_exec($ch);
	var_dump($response, $errNo, $requestContentType, $postvars, $url); die();
	return $response;
}

function getPage( $url ){

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_COOKIEFILE, "/tmp/cookie.txt");
	curl_setopt($ch, CURLOPT_COOKIEJAR, "/tmp/cookie.txt");
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_USERAGENT, '(http://blog.yousoft.ru)');
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}