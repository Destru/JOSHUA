<?php // init
if(preg_match('/iPhone/', $_SERVER['HTTP_USER_AGENT']) || preg_match('/Android/', $_SERVER['HTTP_USER_AGENT'])) header('Location: http://binaerpilot.no/alexander/mobile/'); // redirect
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
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
<?php $dir = opendir('.');
while ($file = readdir($dir)) {
	if(stristr($file, 'theme.')) {
		$title = str_replace('theme.','',str_replace('.css','',$file));
		print "\t".'<link rel="alternate stylesheet" type="text/css" href="'.$file.'" title="'.$title.'" media="screen"/>'."\r";
	}
}
closedir($dir); ?>
	<script type="text/javascript" src="scripts/jquery.js"></script>
	<script type="text/javascript" src="scripts/jquery.ui.custom.js"></script>
	<script type="text/javascript" src="joshua.external.js"></script>
	<script type="text/javascript" src="joshua.js"></script>
</head>
<body><div id="joshua">Booting up the jQuery Operating System...</div>
	<div id="desktop"><?php include 'desktop.joshua.php'; include 'desktop.links.php'; ?></div>
	<div id="apps"><?php include 'app.profile.php'; include 'app.config.php'; include 'app.gallery.php'; include 'app.music.php'; ?></div>
	<div id="games"><?php include 'game.superplastic.php'; ?></div>
<?php include 'analytics.html'; ?>
</body>
</html>