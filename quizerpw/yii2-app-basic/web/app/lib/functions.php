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