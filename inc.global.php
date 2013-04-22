<?php // global settings
if(strpos($_SERVER['HTTP_HOST'], 'joshua.chronicless.com') !== false) {
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: http://joshua.chronicless.com");
	exit();
}
$version = "9.6";
$versionName = "Mono";
$defaultTheme = "mono";
$header = '<b>JOSHUA</b> <span id="version">'.$version.'</span> <span class="dark">'.$versionName.'</span>';
$title = 'JOSHUA '.$version.': ';
$termPrompt = $_SERVER['REMOTE_ADDR'].'@<b>JOSHUA</b>/>&nbsp;';
$joshua = "<b>JOSHUA:</b> ";
$expires = time()+60*60*24*365;
date_default_timezone_set("Europe/Oslo");
?>