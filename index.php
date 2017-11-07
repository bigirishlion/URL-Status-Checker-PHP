<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>301 URL Checker</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="includes/styles.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <!--<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>-->
          <a class="navbar-brand" href="#">URL Status Checker</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
        </div><!--/.nav-collapse -->
      </div>
    </nav>
<div class="container" class="row">
	<div class="col-md-12">
<h1>Check URL Statuses</h1>
<form method="post" fole="form">
	<div class="form-group">
			<label for="urls">Enter ULRs below (One Per Line)</label><br />
			<textarea class="form-control" id="urls" name="urls" rows="20" cols="100"></textarea>
	</div>	
	<div class="checkbox">
			<label><input type="checkbox" name="SaveFile" id="SaveFile" /><b>Would you like to save a file?</b></label>
		</div>
	<div class="form-group sitename">
			<label for="siteName">Enter Your Site Name</label>
			<div class="input-group">
				<span class="input-group-addon">www.</span>
				<input class="form-control" type="text" id="siteName" name="siteName" />
				<span class="input-group-addon">.com</span>
			</div>
	</div>
	<div id="submit" class="form-group">
		<input type="submit" value="Check URLs" name="submit" class="btn btn-default" />
	</div>
	<div class="errors"></div>
</form>
		<div id="counter"></div>
		<div id="returnHTML"></div>
		<div class="export"><a class="btn btn-primary" href="#">Export File</a></div>
</div>
</div>
<script type="text/javascript">

	$(function(){
		$('#SaveFile').click(function(event) {
			if ($(this).is(':checked')) {
				$('.sitename').show();
			} else {
				$('.sitename').hide();
			}
		});
	})

	var splitURL;
	var siteName;
	var urlIndex;
	var saveFile;
	var errors;

	$('form').submit(function(event) {

		errors = false;

		var urls = $('#urls').val();
		var showAllRedirect = $('#show_array').val();
		siteName = $('#siteName').val();
		saveFile = 'off';
		saveFile = ($('#SaveFile').is(':checked')) ? 'on' : 'off';

		$('.form-group').removeClass('has-error');

		if (urls == "") {
			$('#urls').parent('.form-group').addClass('has-error');
			$('.errors').html('<p>Please add URL(s)</p>');
			errors = true;
		}

		if (saveFile == 'on') {
			if(siteName == ''){
				$('#siteName').parents('.form-group').addClass('has-error');
				$('.errors').html('<p>Please add a Site Name</p>');
				errors = true;
			} else {
				siteName = siteName.replace(/ /g, '-');
				siteName = siteName.replace(/[^A-Za-z0-9\-]/g, '');
			}
		}

		if (!errors) {		
			// call ajax for each url			
			$('#submit').html('<img src="includes/images/loading.gif" style="width:250px;" />');
			splitURL = urls.split('\n');

			function cleanArray(actual){
			  var newArray = new Array();
			  for(var i = 0; i<actual.length; i++){
			      if (actual[i]){
			        newArray.push(actual[i]);
			    }
			  }
			  return newArray;
			}

			callAjax(cleanArray(splitURL));
		};
		return false
	});

	function callAjax(urlArray) {

		var counter = 0;
		urlLength = urlArray.length;
		var urls = urlArray;

		$('#counter').append('<p><span class="num">'+ counter +'</span> files complete out of '+urlLength + '</p>');
		$('#returnHTML').append('<table class="table table-bordered js-options-table"><tr><td>URL</td><td>Final URL</td><td>Response</td><td># of Redirects</td><td>Errors</td></tr></table>');

		function recursiveAjax(){
			if (counter == 0) {
				urlIndex = 'first';
			} else if (counter == urlLength - 1) {
				urlIndex = 'last';
				if (saveFile == 'on') {
					$('.export').show().find('a').attr('href', 'includes/export/'+siteName+'-url-checker-export.csv');
				};
			} else {
				urlIndex = '';
			}
			$.ajax({        
			     url:'includes/url-ajax-page.php',        
			     type:'POST',  
			     dataType:'json',            
			     data:{submit:urls[counter],site_name:siteName,url_index:urlIndex,save_file:saveFile},         
			     success:function(json){
			     	$('#submit').html(' ');
			     	if(counter < urlLength){
			     		counter++;
						$('#counter .num').text(counter);
						var error = '';
						var errorClass = '';
						if (json.errors != null){
							error = '<a href="#" title="'+json.errors+'">Error</a>';
							errorClass = 'error';
						}
						var status = json.status;
			     		$('#returnHTML table').append('<tr class="'+ errorClass +' code'+status+'"><td>'+json.url+'</td><td class="final">'+json.final_url+'</td><td>'+json.status+'</td><td>'+json.redirect_num+'</td><td>'+ error +'</td></tr>');
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