<?php

require_once('classes/curl-class.php');
require_once('classes/helper-class.php');

// instantiate helper class
$helper = new Helper();

if (isset($_POST['submit'])) {

	// grab post variables
	$url = $_POST['submit'];
	$site_name = $_POST['site_name'];
	$save_file = $_POST['save_file'];

	// clean the site_name variable
	$site_name = $helper->clean($site_name);

	// setup the curl object
	$cc = new myCurl($url);
	$cc->createCurl();
	$status = $cc->getHttpStatus();
	$redirect_num = $cc->getNumberOfRedirects();
	$error = $cc->getErrors();
	$last_url = $cc->getLastEfectiveUrl();

	// Add status code for 301s
	if ($redirect_num >= 1) {
		$status = '301-'.$status;
	} else{
		$redirect_num = '';
	}

	// if save file is active, create export
	if ($save_file == 'on') {
		$file_path = 'export/'.$site_name.'-url-checker-export.csv';
		$txt = "{$url},{$status},{$redirect_num}\n";
		file_put_contents($file_path, $txt, FILE_APPEND | LOCK_EX);
	}

	// create array for json objects
	$arr = array('url'=>$url, 'final_url' => $last_url, 'status'=>$status, 'redirect_num' => $redirect_num, 'errors' => $error);

	// echo json
	echo json_encode($arr);

}


