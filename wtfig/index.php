<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="The world's simplest (and cutest) FIGlet generator. Create awesome ASCII logos. For hardcore nerds only." />
	<meta name="keywords" content="figlet, generator, script, simple, clean, signature, ascii, ansi, old school, nerd" />
	<meta name="robots" content="index,follow,noarchive" />
	<meta name="author" content="alexander@binaerpilot.no" />
	<meta name="language" content="en" />
	<title>Simple FIGlet Maker - wtFIG</title>
	<link rel="shortcut icon" href="http://binaerpilot.no/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="screen.css" media="screen" />
	<script type="text/javascript" src="http://binaerpilot.no/alexander/scripts/jquery.js"></script>
	<script type="text/javascript" src="zeroclipboard/ZeroClipboard.js"></script>
	<script language="javascript">
		$(document).ready(function(){
			clip = new ZeroClipboard.Client();
			clip.setHandCursor(true);
			clip.addEventListener('complete', copied);
			var output = jQuery('#output').html();
			clip.setText(output);
			clip.glue('copy');
			function copied(){
				$('#message').addClass('notice').html('Done!').fadeIn("fast").animate({foo: 1}, 1000).fadeOut("fast");
			};
		});
	</script>

</head>
<body>
<div id="wrapper">
	<h1><img src="logo.png" alt="Simple Figlet Maker: wtFIG?!" /></h1>
	<form method="post"><div id="wtfig"><?php // pick a random font
	if(!empty($_POST['caption'])) $caption = $_POST['caption'];
	else if(empty($caption)) {
		$captions = array("Kewl","ASCII","Hacker","Defaced","SMURF","Packetz","Scriptkid","Penguin");
		$random = rand(0,count($captions)-1);
		$caption = $captions[$random];
	} 
	print '<input type="text" id="caption" name="caption" value="'.$caption.'" class="text" />';
	// get available fonts
	$dir = scandir("fonts/");
		foreach($dir as $file) {
		if(strpos($file,".flf"))
		$files[] = $file;
	}
	if(empty($_POST['font'])) {
		$random = rand(0,count($files)-1);
		$font = $files[$random];
	}
	else {
		$font = $_POST['font'];
	}
	sort($files);
	print '<select id="font" name="font">';
	foreach($files as $file) {
		$fontName = ucfirst(str_replace('.flf', '', $file));
		if($font == $file) {
			print '  <option value="'.$file.'" selected>'.$fontName.'</option>'."\r";
		}
		else {
			print '  <option value="'.$file.'">'.$fontName.'</option>'."\r";
		}
	}
	print '</select>';
	print '<input type="submit" id="submit" name="submit" value="Figletize" />';
	?></div></form><div class="spacing"></div>
	<?php print '<pre id="output">';
	// load class
	require("class.figlet.php");
	$phpFiglet = new phpFiglet();
	if ($phpFiglet->loadFont("fonts/".$font)) {
		$wtFIG = $phpFiglet->fetch($caption);
		print $wtFIG;
	}
	print '</pre>'; ?>
	<div style="position:relative;">
		<div id="copy" class="button">Copy to clipboard</div>
	</div>
	<div id="message"></div>
	<br class="clear"/>
	<div id="footer">
		<p><a href="http://binaerpilot.no/alexander/">Powered by <strong>Joshua</strong> (jQuery Operating System, HUA!).</a></p>
	</div>
</div>
<?php include '../analytics.html';?>
</body>
</html>
