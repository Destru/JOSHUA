<?php session_start(); // initialize session
	// global settings
	$version = '9.3';
	$versionName = 'Mono';
	$defaultTheme = 'mono';
	$header = '<b>JOSHUA</b> <span id="version">'.$version.'</span> <span class="dark">'.$versionName.'</span>';
	$title = 'JOSHUA '.$version.': ';
	$termPrompt = $_SERVER['REMOTE_ADDR'].'@<b>JOSHUA</b>/>&nbsp;';
?>
<!doctype html>
<html lang="en">
<!--
	Why are you listening to this song backwards?
	You could have been on a date with a girl.
 -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="robots" content="index,follow,noarchive">
	<meta name="language" content="en">
	<title>JOSHUA (jQuery Operating System, HUA!)</title>
	<meta name="description" content="Personal homepage and playground of Alexander Støver. Built around a jQuery operating system (command prompt) named Joshua. Go Team Norway!">
	<meta name="keywords" content="alexander, støver, alexander støver, stoever, astoever, destru kaneda, destru, destryu, jquery, os, command-line, prompt, shell, emulator, javascript">
	<meta name="author" content="alexander@binaerpilot.no">
	<link rel="icon" type="image/png" href="images/favicon.png">
	<link rel="image_src" href="images/thumbnail.png">
	<link rel="stylesheet" type="text/css" href="joshua.css" media="screen">
<?php // theme handling
	$theme = $_COOKIE['theme'];
	$nextgenThemes = array('carolla', 'contra', 'penguin', 'white');
	if(in_array($theme, $nextgenThemes)) echo "\t".'<link rel="stylesheet" type="text/css" href="themes/nextgen.css" media="screen">'."\n"; // next-gen stylesheets
	echo "\t".'<link rel="stylesheet" type="text/css" href="themes/'.$theme.'.css" media="screen">'."\n";
?>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript" src="resources/jquery-ui-1.8.21.custom.min.js"></script>
	<script type="text/javascript" src="resources/joshua.external.js"></script>
	<script type="text/javascript">
		// global settings
		var version = '<?php echo $version; ?>',
		header = '<?php echo $header; ?>',
		title = '<?php echo $title; ?>',
		theme = '<?php echo $theme; ?>',
		defaultTheme = '<?php echo $defaultTheme; ?>',
		termPrompt = '<?php echo $termPrompt; ?>',
		nextgenThemes = ["<?php echo implode('","', $nextgenThemes); ?>"];
	</script>
	<script type="text/javascript" src="joshua.js"></script>	
</head>
<body>
	<div id="joshua"></div>
	<div id="desktop"><?php include 'desktop.php' ?></div>
<?php // fetch apps and games
	$d = scandir('.');		
	foreach ($d as $file) {
		if(stristr($file, 'app.')) $app[] = $file;
		if(stristr($file, 'game.')) $game[] = $file;
	}
?>
	<div id="apps"><?php foreach ($app as $file) include $file; ?></div>
	<div id="games"><?php foreach ($game as $file) include $file; ?></div>
	<div id="loader"></div>
	<!-- analytics -->
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-139019-4']);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</body>
</html>