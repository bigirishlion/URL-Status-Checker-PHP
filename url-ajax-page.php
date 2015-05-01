<?php

require_once('curl-class.php');

if (isset($_POST['submit'])) {

	$url = $_POST['submit'];

	$cc = new myCurl($url);
	$cc->createCurl();

	echo '<tr>';
	echo '<td>'. $url. '</td>';
	echo '<td>'. $cc->getHttpStatus() . '</td>';
	echo '<td>'. $cc->getNumberOfRedirects() . '</td>';
	echo '</tr>';

}


