<?php
	// new domain
	if($_SERVER['HTTP_HOST'] != "localhost") {
		if(eregi("jge.binaerpilot.no",$_SERVER['HTTP_HOST'])) {
			header( "HTTP/1.1 301 Moved Permanently" );
			if(!empty($_SERVER['REQUEST_URI'])) {
				$url = 	$_SERVER['REQUEST_URI'];
				header( "Location: http://jgemainframe.com$url" );
			}
			else {
				header( "Location: http://jgemainframe.com/" );
			}
		}
	}
	// php magic
	$path = $_SERVER['DOCUMENT_ROOT'].'/alexander/jge/';
	$root = $_SERVER['DOCUMENT_ROOT'].'/alexander/';
	$site = 'http://jgemainframe.com/';
	if($_SERVER['HTTP_HOST'] == "localhost") $site = 'http://localhost/alexander/jge/';
	include $path.'functions.php';
	if(!empty($_GET['p'])) $p = $_GET['p'];
	else $p = "news";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Jumpgate Evolution Pro Resource - JGE Mainframe</title>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="index,follow,noarchive" />
	<meta name="description" content="Your source for news, rumours and information on JGE." />
	<meta name="keywords" content="jumpgate, jumpgate evolution, jge, news, rumours, tips, tricks, hints, cheat sheets" />
	<link rel="stylesheet" type="text/css" href="<?php print $site;?>screen.css" />
	<link rel="alternate stylesheet" type="text/css" title="solrain" href="<?php print $site;?>solrain.css" />
	<link rel="alternate stylesheet" type="text/css" title="quantar" href="<?php print $site;?>quantar.css" />
	<link rel="shortcut icon" href="http://jgemainframe.com/favicon.ico" />
	<script type="text/javascript" src="http://binaerpilot.no/alexander/scripts/jquery.js"></script>
	<script type="text/javascript" src="http://binaerpilot.no/alexander/scripts/jquery.corner.js"></script>
	<script type="text/javascript" src="http://binaerpilot.no/scripts/jquery.livetwitter.js"></script>
	<script type="text/javascript" src="http://binaerpilot.no/scripts/thickbox-compressed.js"></script>
	<script type="text/javascript" src="http://binaerpilot.no/scripts/styleswitcher.js"></script>	
	<script type="text/javascript">
		$(document).ready(function(){
			$(".tweet,.news_item,.tear,.ships,#tr a").corner("bevel tl 10px").corner("bottom 10px");
			$("#wrapper").corner("bevel tl 20px");
			$("#menu ul.menu li a:first, #subnav a:first").corner("bevel tl 5px");
			$("#menu ul.menu li a:last, #subnav a:last").corner("bottom 10px");
			$(".icon").corner("bevel bl 10px");
			$(".box a,td.img,#block a").hover(function(){$(this).fadeTo("fast", 1);},function(){$(this).fadeTo("fast", 0.85);});
		});
	</script>
</head>
<body>
<div id="wrapper">
	<div id="header">
		<div class="logo"><a href="http://jgemainframe.com/" title="Jumpgate Evolution Item Database - JGE Mainframe"><img src="<?=$site;?>images/logo.png" alt="" height="68" /></a></div>
		<div id="tr">
			<a onclick="setActiveStyleSheet('');return false;"><img src="<?=$site;?>images/oct.png" height="48" width="48" alt="" /><div class="id"><img src="<?=$site;?>images/oct-name.png" alt=""/></div></a>
			<a onclick="setActiveStyleSheet('solrain');return false;"><img src="<?=$site;?>images/sol.png" height="48" width="48" alt="" /><div class="id"><img src="<?=$site;?>images/sol-name.png" alt=""/></div></a>
			<a onclick="setActiveStyleSheet('quantar');return false;"><img src="<?=$site;?>images/quant.png" height="48" width="48" alt="" /><div class="id"><img src="<?=$site;?>images/quant-name.png" alt=""/></div></a>
		</div>
		<br class="clear"/>
	</div>
	<div id="menu">
		<ul class="menu">
			<li><a href="<?=$site;?>">Latest news and rumours</a></li>
			<li><a href="<?=$site;?>features.html">Features and specs</a></li>
			<li><a href="<?=$site;?>ships.html">Ship concepts</a></li>
			<li><a href="<?=$site;?>gec/">G.E.C. Squadron</a></li>
			<li><a href="<?=$site;?>about.html">About JGE Mainframe</a></li>
			</ul>
		<div class="box"><? include $path.'inc.box.php'; ?></div>
	</div>
	<div id="content">
<?php include $path.$p.'.html'; ?>
	</div>
	<br class="clear"/>
</div>
<div id="props">
	Tetris legend <a href="http://binaerpilot.no/alexander" title="Alexander St&oslash;ver is a gigantic nerd">Alexander St&oslash;ver</a> made this.
	Same guy who makes that <a href="http://binaerpilot.no" title="Insane Nintendo Beats">crazy norwegian electro</a> for your headphones.
</div>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-139019-5");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>
</html>