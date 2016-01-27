<?php // alexander@binaerpilot.no
	include 'inc.global.php';
	function error($msg) {} // catch-all ?>
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
	<meta property="og:image" content="http://joshua.einhyrning.com/images/opengraph.jpg?<?= $version ?>">
	<meta property="og:site_name" content="jQuery Operating System, HUA!">
	<meta property="og:title" content="Personal homepage of Alexander Støver">
	<meta property="og:url" content="http://joshua.einhyrning.com/">
	<meta property="og:description" content="Quite possibly the nerdiest homepage ever made.">
	<meta property="og:type" content="website">
	<link rel="image_src" href="http://joshua.einhyrning.com/images/opengraph.jpg?<?= $version ?>">
	<link rel="icon" type="image/png" href="images/favicon.png?<?= $version ?>">
	<link rel="stylesheet" type="text/css" href="joshua.css?<?= $version ?>" media="screen">
<?php // theme handling
	$theme = $_COOKIE['theme'];
	if (empty($theme)) $theme = $defaultTheme;
	if (in_array($theme, $nextgenThemes)) echo "\t".'<link rel="stylesheet" type="text/css" href="themes/nextgen.css?'.$version.'" media="screen">'."\n";
	echo "\t".'<link rel="stylesheet" type="text/css" href="themes/'.$theme.'.css?'.$version.'" media="screen">'."\n";
?>
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-139019-9', 'einhyrning.com');
	  ga('send', 'pageview');
	</script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
	<script src="resources/joshua.external.min.js?<?= $version ?>"></script>
	<script src="resources/joshua.effects.min.js?<?= $version ?>"></script>
	<script>
		// global settings
		var version = '<?= $version ?>',
		title = '<?= $title ?>',
		theme = '<?= $theme ?>',
		termPrompt = '<?= $termPrompt ?>',
		nextgenThemes = ["<?= implode('","', $nextgenThemes) ?>"];
	</script>
	<script src="joshua.js?<?= $version ?>"></script>
</head>
<body>
	<div id="wrapper">
		<div id="joshua">
			<h1><?= $header ?></h1>
			<div id="output"></div>
			<div id="input">
				<input type="text" id="prompt" autocomplete="off">
			</div>
		</div>
		<div id="desktop">
			<ul id="windows" class="icons">
                <li><a data-window="customize" title="Customization options and effects">Customize</a>
                <li><a data-window="superplastic" title="You must escape!">Superplastic</a>
                <!-- outdated API <li><a data-window="videos" title="Stream (long) videos on YouTube">Video Player</a> -->
                <li><a data-window="music" title="Listen to my music">MP3 Player</a>
			</ul>
			<ul id="links" class="icons">
    			<li><a class="external" href="http://binaerpilot.no" title="Robot music for hackers and other nerds">BINÆRPILOT</a>
    			<li><a class="external" href="http://chronicless.einhyrning.com/" title="JSON API for The Secret World">ChronicLESS</a>
    			<li><a class="external-page" href="wtfig/" title="Simple online FIGlet generator">wtFIG</a>
    			<li><a class="external" href="http://einhyrning.com/" title="VGhlIG9jZWFuIG9mIHNwYWNlDQpJcyBleGlzdGVuY2UgaXMgcHVycG9zZQ0KQ2hhbmdlIGlzIGZvcmV2ZXI=">Einhyrning</a>
			</ul>
		</div>
		<div id="apps">
			<div id="speak"></div>
			<?php // apps
				$d = scandir('.');
				foreach ($d as $file) {
					if (stristr($file, 'app.')) include $file;
				} ?>
	</div>
		<div id="loader"></div>
	</div>
</body>
</html>
