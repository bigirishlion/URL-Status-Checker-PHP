<?php

require_once('classes/curl-class.php');
require_once('classes/helper-class.php');

$helper = new Helper();

if (isset($_POST['submit'])) {

	$url = $_POST['submit'];
	$site_name = $_POST['site_name'];
	$save_file = $_POST['save_file'];

	$cc = new myCurl($url);
	$cc->createCurl();

	$site_name = $helper->clean($site_name);

	$status = $cc->getHttpStatus();
	$redirect_num = $cc->getNumberOfRedirects();
	$error = $cc->getErrors();

	if ($redirect_num >= 1) {
		$status = '301-'.$status;
	} else{
		$redirect_num = '';
	}

	if ($save_file == 'on') {
		$file_path = 'export/'.$site_name.'-url-checker-export.csv';
		$txt = "{$url},{$status},{$redirect_num}\n";
		file_put_contents($file_path, $txt, FILE_APPEND | LOCK_EX);
	}

	$arr = array('url'=>$url, 'status'=>$status, 'redirect_num' => $redirect_num, 'errors' => $error);

	echo json_encode($arr);

}


