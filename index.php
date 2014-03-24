<?php include 'inc.global.php'; ?>
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
	<title>JOSHUA (jQuery Operating System, HUA!) - Alexander Støver's Homepage</title>
	<meta name="description" content="Personal homepage and playground of Alexander Støver. Built around a jQuery operating system (command prompt) named JOSHUA. Go Team Norway!">
	<meta name="keywords" content="alexander, støver, alexander støver, stoever, astoever, destru kaneda, destru, destryu, jquery, os, command-line, prompt, shell, emulator, javascript">
	<meta name="author" content="alexander@binaerpilot.no">
		<!-- open graph -->
		<meta property="og:image" content="http://joshua.einhyrning.com/images/opengraph.jpg">
		<meta property="og:site_name" content="jQuery Operating System, HUA!"> 
		<meta property="og:title" content="Personal homepage of Alexander Støver"> 
		<meta property="og:url" content="http://joshua.einhyrning.com/"> 
		<meta property="og:description" content="Quite possibly the nerdiest homepage ever made."> 
		<meta property="og:type" content="website">
		<!-- fallback -->
		<link rel="image_src" href="http://joshua.einhyrning.com/images/opengraph.jpg">
	<link rel="icon" type="image/png" href="images/favicon.png">
	<link rel="stylesheet" type="text/css" href="joshua.css" media="screen">
<?php // theme handling
	$theme = $_COOKIE['theme'];
	$nextgenThemes = array('carolla', 'contra', 'penguin', 'white');
	if(in_array($theme, $nextgenThemes)) echo "\t".'<link rel="stylesheet" type="text/css" href="themes/nextgen.css" media="screen">'."\n"; // next-gen stylesheets
	echo "\t".'<link rel="stylesheet" type="text/css" href="themes/'.$theme.'.css" media="screen">'."\n";
?>
	<!-- analytics -->
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-139019-9', 'einhyrning.com');
	  ga('send', 'pageview');
	</script>
	<!-- javascript -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
	<script src="resources/joshua.external.js"></script>
	<script src="resources/joshua.effects.js"></script>
	<script>
		// global settings
		var version = '<?php echo $version; ?>',
		title = '<?php echo $title; ?>',
		theme = '<?php echo $theme; ?>',
		defaultTheme = '<?php echo $defaultTheme; ?>',
		termPrompt = '<?php echo $termPrompt; ?>',
		nextgenThemes = ["<?php echo implode('","', $nextgenThemes); ?>"];
	</script>
	<script src="joshua.js"></script>	
</head>
<body>
	<div id="wrapper">
		<div id="joshua">
			<h1><?php echo $header; ?></h1>
			<div id="output"></div>
			<div id="input">
				<input type="text" id="prompt" autocomplete="off">
			</div>
		</div>
		<div id="desktop"><?php include 'inc.desktop.php'; ?></div>
<?php // fetch apps and games
	$d = scandir('.');		
	foreach ($d as $file) {
		if(stristr($file, 'app.')) $app[] = $file;
		if(stristr($file, 'game.')) $game[] = $file;
	} ?>
		<div id="apps"><?php foreach ($app as $file) include $file; ?></div>
		<div id="games"><?php foreach ($game as $file) include $file; ?></div>
		<div id="loader"></div>
	</div>
</body>
</html>