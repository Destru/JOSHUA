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
	<title>JOSHUA (jQuery Operating System, HUA!) - Alexander St&oslash;ver</title>
	<meta name="description" content="Personal homepage and playground of Alexander Støver. Built around a jQuery operating system (command prompt) named Joshua. Go Team Norway!" />
	<meta name="keywords" content="alexander, støver, alexander støver, stoever, astoever, destru kaneda, destru, destryu, jquery, os, command-line, prompt, shell, emulator, javascript" />
	<meta name="author" content="alexander@binaerpilot.no" />
	<link rel="icon" type="image/png" href="images/favicon.png" />
	<link rel="stylesheet" type="text/css" href="joshua.css" media="screen" />
<?php // yeah, I should change how this works 
foreach ($theme as $file) {
	$title = str_replace('theme.','',str_replace('.css','',$file));
	print "\t".'<link rel="alternate stylesheet" type="text/css" href="'.$file.'" title="'.$title.'" media="screen"/>'."\r";
} ?>
	<script type="text/javascript" src="scripts/jquery.js"></script>
	<script type="text/javascript" src="scripts/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="joshua.external.js"></script>
	<script type="text/javascript" src="joshua.js"></script>
</head>
<body><div id="joshua">Booting up the jQuery Operating System...</div>
<div id="desktop"><?php include 'desktop.php' ?></div>
<div id="apps"><?php foreach ($app as $file) include $file; ?></div>
<div id="games"><?php foreach ($game as $file) include $file; ?></div>
<?php include 'analytics.html'; ?>
</body>
</html>