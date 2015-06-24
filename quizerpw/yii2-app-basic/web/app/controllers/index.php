<?php
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
	
	$view['success'] = true;

}

if (!$view['success']) {

	$html = getPage("http://sevastopol.gov.ru/feedback/new/");

	preg_match_all("'src=\"\/bitrix\/tools\/captcha\.php\?captcha_sid=(.*?)\"'ims", $html, $p);
	$sid = $p[1][0];
	file_put_contents('/tmp/app_captcha_sid', $sid);


	preg_match_all("'\'bitrix_sessid\':\'(.*?)\''ims", $html, $p);
	$sessId = $p[1][0];	
	file_put_contents('/tmp/app_sess_id', $sessId);
	
}