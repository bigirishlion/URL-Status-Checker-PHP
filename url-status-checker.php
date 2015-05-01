<link rel="stylesheet" type="text/css" href="styles.css">
<h1>Check URL Statuses</h1>
<p>Enter ULRs below (One Per Line)</p>
<form method="post">
	<textarea name="urls" rows="20" cols="100"></textarea>
	<br />
	<br />
	<label for="show_array">Do you want to see just the list of redirects?</label>
	<select name="show_array" >
		<option value="no">No</option>
		<option value="yes">Yes</option>
	</select>
	<br />
	<br />
	<input type="submit" value="Check URLs" name="submit" />
</form>

<?php
$filename = 'statuses.csv';

if (file_exists($filename)) {
    echo "<br /><a href=\"statuses.csv\">Download CSV</a><br /><br />";
}

/*
http://www.w3schools.com/
http://themeforest.net/asasasdasdas
http://themeforest.net/
http://google.com/
*/

// echo "<br />";
if (isset($_POST['submit'])) {

	$myfile = fopen("statuses.csv", "w") or die("Unable to open file!");
	$txt = "URL,Status,NewLocation,NewLocationStatus\r\n";
	$urls = $_POST['urls'];
	$urlsInArray = explode("\n", $urls);
	$itemCount = count($urlsInArray);
	$maxUrls = 1000;
	if ($itemCount > $maxUrls) {
		echo "You added {$maxUrls} URLs which is too many. please only add a maximum of {$maxUrls} URLs<br />";
		echo "<a href='./url-status-checker.php'>Refresh Page</a>";
		return;
	}
	//print_r($urlsInArray);
	echo "<table><tr><th>Original URL</th><th>Status</th><th>Final Location URL</th><th>Status</th></tr>";
	foreach ($urlsInArray as $url) {
		//echo $url.'<br />';
		$url = trim($url);
		$urlArray = get_headers($url,1);
		//echo $urlArray[0];
		$location = '';
		$toLocation = '';
		$nextLocation = '';
		if ($urlArray[0] == 'HTTP/1.0 301 Moved Permanently' || $urlArray[0] == 'HTTP/1.1 301 Moved Permanently' || $urlArray[0] == 'HTTP/1.1 302 Found') {
			$finalLocation = $urlArray['Location'];		
			if (is_array($finalLocation)) {
				// make finalLocation last item in array	
				if ($_POST['show_array'] == 'yes') {
					print_r($finalLocation);
					return;
				}
				$finalLocation = end($finalLocation);
			}			
			$location = ' To - ' . $finalLocation;
			$toLocation = $finalLocation;
			$toLocationArray = get_headers($toLocation,1);
			/*print_r($toLocationArray);
			return;*/
			$nextLocation = " status = " . ($toLocationArray[0]);
			//print_r($finalURL);
		}
		echo '<tr>';
		echo '<td>'. $url. '</td>';
		echo '<td>'. $urlArray[0] . '</td>';
		echo '<td>'. $location . '</td>';
		echo '<td>'. $nextLocation . '</td>';
		echo '</tr>';
		$txt .= $url.','. $urlArray[0]. ',' . $toLocation . ',' . $nextLocation . "\r\n"; 
		
	}
	echo "</table>";

	fwrite($myfile, $txt);
	fclose($myfile);

} 

?>