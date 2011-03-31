<?php session_start(); // initialize session
	// global settings
	$version = "8.2";
	$name = "Stable";
	$header = '<b>JOSHUA</b> <span id="version">'.$version.'</span> <span class="dark">'.$name.'</span>';
	$title = "JOSHUA: ";
	$prompt = "<strong>Guest</strong>@JOSHUA >&nbsp;";
	// mobile placeholder
	if(preg_match('/iPhone/', $_SERVER['HTTP_USER_AGENT']) || preg_match('/Android/', $_SERVER['HTTP_USER_AGENT'])) header('Location: http://binaerpilot.no/alexander/mobile/'); // redirect
?>
<!DOCTYPE html>
<html>
<!--
	Why are you listening to this song backwards?
	You could have been on a date with a girl.
 -->
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="index,follow,noarchive" />
	<meta name="language" content="en" />
	<title>JOSHUA (jQuery Operating System, HUA!)</title>
	<meta name="description" content="Personal homepage and playground of Alexander Støver. Built around a jQuery operating system (command prompt) named Joshua. Go Team Norway!" />
	<meta name="keywords" content="alexander, støver, alexander støver, stoever, astoever, destru kaneda, destru, destryu, jquery, os, command-line, prompt, shell, emulator, javascript" />
	<meta name="author" content="alexander@binaerpilot.no" />
	<link rel="icon" type="image/png" href="images/favicon.png" />
	<link rel="stylesheet" type="text/css" href="joshua.css" media="screen" />
<?php // theme handling
	$theme = $_COOKIE['theme'];
	$nextgen = array('carolla', 'contra', 'penguin', 'white');
	if(in_array($theme, $nextgen)) {
		echo "\t".'<link rel="stylesheet" type="text/css" href="themes/nextgen.css" media="screen" />'."\n";
	}
	echo "\t".'<link rel="stylesheet" type="text/css" href="themes/'.$theme.'.css" media="screen" />'."\n";
?>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="scripts/joshua.external.js"></script>
	<script type="text/javascript">
		// global settings
		var version = '<?php echo $version; ?>',
		header = '<?php echo $header; ?>',
		title = '<?php echo $title; ?>',
		prompt = '<?php echo $prompt; ?>',
		nextgen = ["<?php echo implode('","', $nextgen); ?>"];
	</script>
	<script type="text/javascript" src="scripts/joshua.js"></script>	
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
	<!-- analytics -->
	<script type="text/javascript">
		var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
		document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
		</script>
		<script type="text/javascript">
		try {
		var pageTracker = _gat._getTracker("UA-139019-4");
		pageTracker._trackPageview();
		} catch(err) {}
	</script>
</body>
</html>