<?php

require_once('classes/curl-class.php');
require_once('classes/print-class.php');

if (isset($_POST['submit'])) {

	$url = $_POST['submit'];

	$cc = new myCurl($url);
	$cc->createCurl();

	$status = $cc->getHttpStatus();
	$redirect_num = $cc->getNumberOfRedirects();

	if ($redirect_num >= 1) {
		$status = '301/'.$status;
	} else{
		$redirect_num = '';
	}

	echo '<tr>';
	echo '<td>'. $url. '</td>';
	echo '<td>'. $status . '</td>';
	echo '<td>'. $redirect_num . '</td>';
	echo '</tr>';

	$items[] = new printObj($status, $redirect_num, $url);

	print_r($items);

}


