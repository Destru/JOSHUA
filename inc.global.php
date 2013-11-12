<?php // global settings
if(strpos($_SERVER['HTTP_HOST'], 'joshua.chronicless.com') !== false) {
	header("HTTP/1.1 301 Moved Permanently"); 
	header("Location: http://joshua.chronicless.com");
	exit();
}
session_start();
$version = "10.3";
$versionName = "Neocom II";
$defaultThemes = array('neocom', 'mono', 'diesel', 'tron', 'lcars');
// $defaultTheme = $defaultThemes[array_rand($defaultThemes)]; // random theme
$defaultTheme = 'neocom';
$header = '<b>JOSHUA</b> <span id="version">'.$version.'</span> <span class="dark">'.$versionName.'</span>';
$title = 'JOSHUA '.$version.': ';
$termPrompt = $_SERVER['REMOTE_ADDR'].'@<b>JOSHUA</b>/>&nbsp;';
$joshua = "<b>JOSHUA:</b> ";
$expires = time()+60*60*24*365;
date_default_timezone_set("Europe/Oslo");

// functions
function get($url, $cache=null, $inline=null) {
	clearstatcache();
	$timeout = 10;
	$secondsBeforeUpdate = 60;
	if(!empty($cache)) {
		$timeout = 10;
		$secondsBeforeUpdate = 60*60*12;
		if(!file_exists($cache) || filesize($cache) == 0) {
			file_put_contents($cache, null);
			$firstRun = true;
		}
		$lastModified = filemtime($cache);
		if(isset($firstRun) || time() - $lastModified > $secondsBeforeUpdate) {
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
			$data = curl_exec($ch);
			curl_close($ch);
			if(!empty($data)) {
				file_put_contents($cache, $data, LOCK_EX);
			}
			else {
				if($inline) error('empty', 1);
				else error('empty');
			}
		}
	}
	else {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}
function load($file, $inline=null) {
	if(file_exists($file)) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		libxml_use_internal_errors(true);
		if($ext == 'xml') {
			$xml = simplexml_load_file($file);
			if($xml) return $xml;
			else {
				if($inline) error('invalidxml', 1);
				else error('invalidxml');
			}
		}
		else if($ext == 'json') {
			$json = json_decode(file_get_contents($file,0,null,null));
			if($json) return $json;
			else error('invalidjson');
		}
		else if($ext == 'data') {
			$dom = new DOMDocument();
			$dom->preserveWhiteSpace = false;
			if($dom->loadHTMLFile($file)) return $dom;
			else error('invalidhtml');
		}
	}
	else {
		if($inline) error('localcache', 1);
		else error('localcache');
	}
}
?>