<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>301 URL Checker</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
<h1>Check URL Statuses</h1>
<p>Enter ULRs below (One Per Line)</p>
<form method="post">
	<textarea id="urls" name="urls" rows="20" cols="100"></textarea>
	<br />
	<br />
	<label for="show_array">Do you want to see just the list of redirects?</label>
	<select id="show_array" name="show_array" >
		<option value="no">No</option>
		<option value="yes">Yes</option>
	</select>
	<br />
	<br />
	<div id="submit">
		<input type="submit" value="Check URLs" name="submit" />
	</div>
</form>
<div id="counter"></div>
<div id="returnHTML"></div>
<script type="text/javascript">

	var splitURL;

	$('form').submit(function(event) {
		var urls = $('#urls').val();
		var showAllRedirect = $('#show_array').val();
		$('#submit').html('<img src="images/loading.gif" style="width:250px;" />');
		
		// call ajax for each url
		splitURL = urls.split('\n');
		callAjax(splitURL);
		return false
	});

	function callAjax(urlArray) {

		var counter = 0;
		urlLength = urlArray.length;
		var urls = urlArray;

		$('#counter').append('<p><span class="num">'+ counter +'</span> files complete out of '+urlLength + '</p>');
		$('#returnHTML').append('<table><tr><td>URL</td><td>Response</td></tr></table>');

		function recursiveAjax(){
			$.ajax({        
			     url:'url-ajax-page.php',        
			     type:'POST',              
			     data:{submit:urls[counter]/*,show_array:showAllRedirect*/},         
			     success:function(HTML){
			     	$('#submit').html(' ');
			     	if(counter < urlLength){
			     		counter++;
						$('#counter .num').text(counter);
			     		$('#returnHTML table').append(HTML);
			     		recursiveAjax();

			     	}
			     }
		     }); 
		}

		recursiveAjax();
	}
</script>
</body>
</html>