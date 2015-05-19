<?php

require_once('classes/curl-class.php');

if (isset($_POST['submit'])) {

	$url = $_POST['submit'];
	$site_name = $_POST['site_name'];

	$cc = new myCurl($url);
	$cc->createCurl();

	$status = $cc->getHttpStatus();
	$redirect_num = $cc->getNumberOfRedirects();

	if ($redirect_num >= 1) {
		$status = '301/'.$status;
	} else{
		$redirect_num = '';
	}

	$file_path = 'export/'.$site_name.'url-checker-export.csv';
	$txt = "{$url},{$status},{$redirect_num}\n";
	file_put_contents($file_path, $txt, FILE_APPEND | LOCK_EX);

	$arr = array('url'=>$url, 'status'=>$status, 'redirect_num' => $redirect_num);

	echo json_encode($arr);

}


