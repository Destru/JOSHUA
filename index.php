<?php // let's grab all this crap!
	$d = scandir('.');
	foreach ($d as $file) {
		if(stristr($file, 'app.')) $app[] = $file;
		if(stristr($file, 'game.')) $game[] = $file;
		if(stristr($file, 'theme.')) $theme[] = $file;
	}
	// business card
	if(preg_match('/iPhone/', $_SERVER['HTTP_USER_AGENT']) || preg_match('/Android/', $_SERVER['HTTP_USER_AGENT'])) header('Location: http://binaerpilot.no/alexander/mobile/'); // redirect
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<!--
	Why are you listening to this song backwards?
	You could have been on a date with a girl.
 -->
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="index,follow,noarchive" />
	<meta name="language" content="en" />
	<title>JOSHUA (jQuery Operating System, HUA!) - Alexander Støver</title>
	<meta name="description" content="Personal homepage and playground of Alexander Støver. Built around a jQuery operating system (command prompt) named Joshua. Go Team Norway!" />
	<meta name="keywords" content="alexander, støver, alexander støver, stoever, astoever, destru kaneda, destru, destryu, jquery, os, command-line, prompt, shell, emulator, javascript" />
	<meta name="author" content="alexander@binaerpilot.no" />
	<link rel="icon" type="image/png" href="images/favicon.png" />
	<link rel="stylesheet" type="text/css" href="joshua.css" media="screen" />
</head>
<body>
	<div id="joshua"></div>
	<div id="desktop"><?php include 'desktop.php' ?></div>
	<div id="apps"><?php foreach ($app as $file) include $file; ?></div>
	<div id="games"><?php foreach ($game as $file) include $file; ?></div>

	<!-- load JS at the bottom to avoid WebKit CSS bug -->
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="scripts/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="scripts/joshua.external.js"></script>
	<script type="text/javascript" src="scripts/joshua.js"></script>

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