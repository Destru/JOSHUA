
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
		<meta property="og:image" content="http://joshua.einhyrning.com/images/thumbnail.jpg"> 
		<meta property="og:site_name" content="jQuery Operating System, HUA!"> 
		<meta property="og:title" content="Personal homepage of Alexander Støver"> 
		<meta property="og:url" content="http://joshua.einhyrning.com/"> 
		<meta property="og:description" content="Quite possibly the nerdiest homepage ever made."> 
		<meta property="og:type" content="website">
	<link rel="icon" type="image/png" href="images/favicon.png">
	<link rel="stylesheet" type="text/css" href="joshua.css" media="screen">
<?php // theme handling
	$theme = $_COOKIE['theme'];
	$nextgenThemes = array('carolla', 'contra', 'penguin', 'white');
	if(in_array($theme, $nextgenThemes)) echo "\t".'<link rel="stylesheet" type="text/css" href="themes/nextgen.css" media="screen">'."\n"; // next-gen stylesheets
	echo "\t".'<link rel="stylesheet" type="text/css" href="themes/'.$theme.'.css" media="screen">'."\n";
?>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script src="resources/joshua.external.js"></script>
	<script>
		// global settings
		var version = '<?php echo $version; ?>',
		header = '<?php echo $header; ?>',
		title = '<?php echo $title; ?>',
		theme = '<?php echo $theme; ?>',
		defaultTheme = '<?php echo $defaultTheme; ?>',
		termPrompt = '<?php echo $termPrompt; ?>',
		nextgenThemes = ["<?php echo implode('","', $nextgenThemes); ?>"];
	</script>
	<script src="joshua.js"></script>	
</head>
<body>
	<div id="joshua"></div>
	<div id="desktop"><?php include 'inc.desktop.php'; ?></div>
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
		_gaq.push(['_setAccount', 'UA-139019-9']);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
</body>
</html>