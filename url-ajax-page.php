<?php

require_once('curl-class.php');

/*
http://www.w3schools.com/
http://themeforest.net/asasasdasdas
http://themeforest.net/
http://google.com/
*/

// echo "<br />";
if (isset($_POST['submit'])) {

	$url = $_POST['submit'];
	//$urlSuccess = get_headers($url,1);

	$cc = new myCurl($url);
	$cc->createCurl();

	/*echo '<tr>';
	echo '<td>'. $url. '</td>';
	echo '<td>'. $urlSuccess[0] . '</td>';
	echo '</tr>';*/

	echo '<tr>';
	echo '<td>'. $url. '</td>';
	echo '<td>'. $cc->getHttpStatus() . '</td>';
	echo '<td>'. $cc->getNumberOfRedirects() . '</td>';
	echo '</tr>';

}


