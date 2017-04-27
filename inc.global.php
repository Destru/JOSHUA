<?php
if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
  ini_set("zlib.output_compression", 1);
}

function update($force=null) {
	$cache = 'static.json';
	$secondsBeforeUpdate = 60*60*24;
	if (!file_exists($cache) || filesize($cache) == 0) {
		file_put_contents($cache, null);
		$force = true;
	}
	$lastModified = filemtime($cache);
	if (isset($force) || time() - $lastModified > $secondsBeforeUpdate) {
		$commits = json_decode(get('https://api.github.com/repos/destru/joshua/commits', null, 1));
		$leagueVersion = json_decode(get('http://ddragon.leagueoflegends.com/realms/na.json', null, 1));

		if (count($commits) && !empty($leagueVersion->v)) {
			$fp = fopen($cache, 'w');
			fwrite($fp, '{');
			fwrite($fp, '"sha": "'.$commits{0}->sha.'",');
			fwrite($fp, '"leagueVersion": "'.$leagueVersion->v.'"');
			fwrite($fp, '}');
			fclose($fp);
		}
	}
}

session_start();
update();
$version = "12.1";
$versionName = "NeoCom";
$defaultTheme = "neocom";
$nextgenThemes = array('carolla', 'contra', 'rachael', 'whitewall');
$staticData = load('static.json');
$sha = $staticData->sha;
$header = '<b>JOSHUA</b> <span class="version">'.substr($sha, 0, 7).'</span> <span class="dark">'.$versionName.'</span>';
$title = 'JOSHUA ';
$termPrompt = $_SERVER['REMOTE_ADDR'].'@<b>JOSHUA</b>/>&nbsp;';
$joshua = "<b>JOSHUA:</b> ";
$cookieExpires = time()+60*60*24*365;
date_default_timezone_set("America/New_York");

function get($url, $cache=null, $inline=null) {
	clearstatcache();
	$timeout = 10;
	$secondsBeforeUpdate = 600;
	$cURLHeader = array(
	  'User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12',
	  'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
	  'Accept-Language: en-us,en;q=0.5',
	  'Accept-Encoding: gzip,deflate',
	  'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
	  'Keep-Alive: 115',
	  'Connection: keep-alive',
	);

	if (!empty($cache)) {
		$timeout = 10;
		if (!file_exists($cache) || filesize($cache) == 0) {
			file_put_contents($cache, null);
			$firstRun = true;
		}
		$lastModified = filemtime($cache);
		if (isset($firstRun) || time() - $lastModified > $secondsBeforeUpdate) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_VERBOSE, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $cURLHeader);
			curl_setopt($ch, CURLOPT_ENCODING , "gzip");
			$data = curl_exec($ch);
			curl_close($ch);
			if (!empty($data)) {
				file_put_contents($cache, $data, LOCK_EX);
			}
		}
	}
	else {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $cURLHeader);
		curl_setopt($ch, CURLOPT_ENCODING , "gzip");
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

function load($file, $inline=null) {
	if (file_exists($file)) {
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		libxml_use_internal_errors(true);
		if ($ext == 'xml') {
			$xml = simplexml_load_file($file);
			if ($xml) return $xml;
			else {
				if ($inline) error('invalidxml', 1);
				else error('invalidxml');
			}
		}
		else if ($ext == 'json') {
			$json = json_decode(file_get_contents($file,0,null,null));
			if ($json) return $json;
			else {
				if ($inline) error('invalidjson', 1);
				else error('invalidjson');
			}
		}
		else if ($ext == 'data') {
			$dom = new DOMDocument();
			$dom->preserveWhiteSpace = false;
			if ($dom->loadHTMLFile($file)) return $dom;
			else error('invalidhtml');
		}
	}
	else {
		if ($inline) error('localcache', 1);
		else error('localcache');
	}
}

function userInput($string) {
	return strip_tags(trim($string));
}

function humanFileSize($size, $unit='') {
  if ((!$unit && $size >= 1<<30) || $unit == 'GB')
    return number_format($size/(1<<30),2).' GB';
  if ((!$unit && $size >= 1<<20) || $unit == 'MB')
    return number_format($size/(1<<20),2).' MB';
  if ((!$unit && $size >= 1<<10) || $unit == 'KB')
    return number_format($size/(1<<10),2).' KB';
  return number_format($size).' bytes';
}
?>
