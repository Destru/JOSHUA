<?php // joshua engine <alexander@binaerpilot.no>
session_start(); // sudo commands
include 'inc.global.php';
if($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "127.0.0.1") $dev = 1; // development mode set
if(!empty($_POST['command'])) $command = strtolower(strip_tags(trim($_POST['command'])));
if(!empty($_POST['option'])) $option = strip_tags(trim($_POST['option']));
if(!empty($_POST['dump'])) $dump = strip_tags(trim($_POST['dump']));
if(!empty($option) && $option == "undefined") unset($option);
if(!empty($dump) && $dump == "undefined") unset($dump);
unset($output);

// functions
function error($id, $inline=null){
	global $error, $command, $prompt, $joshua;
	if(!$inline) print $prompt;
	print '<p class="error">'.$joshua.$error[$id].'</p>';
	die();
}
function output($response){
	global $output, $command, $option, $prompt;
	if(stristr($response,'<p') || stristr($response,'<table')) print $prompt.$response;
	else print $prompt.'<p>'.$response.'</p>';
	$output = 1;
}
function get($url, $cache=null, $inline=null){
	global $dev;
	$timeout = 10;
	$secondsBeforeUpdate = 60;
	if(!isset($dev)){
		if(!empty($cache)){
			if(!file_exists($cache)) {
				touch($cache);
				$firstRun = true;
			}
			$lastModified = filemtime($cache);
			if(isset($firstRun) || time() - $lastModified > $secondsBeforeUpdate){
				$ch = curl_init();
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
				$urlData = curl_exec($ch);
				curl_close($ch);
				if(!empty($urlData)){
					$handle = fopen($cache, "w");
					fwrite($handle, $urlData);
					fclose($handle);
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
			$urlData = curl_exec($ch);
			return $urlData;
			curl_close($ch);
		}
	}
	else {
		if(empty($cache)) error('noreturn');
	}
}
function load($file, $inline=null){
	if(file_exists($file)){
		$ext = pathinfo($file, PATHINFO_EXTENSION);
		libxml_use_internal_errors(true);
		if($ext == 'xml'){
			if(simplexml_load_file($file) && filesize($file) > 350) return simplexml_load_file($file);
			else {
				if($inline) error('invalidxml', 1);
				else error('invalidxml');
			}
		}
		else if($ext == 'json'){
			$json = file_get_contents($file,0,null,null);
			if($json) return json_decode($json);
			else error('invalidjson');
		}
		else if($ext == 'data'){
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
function microtimer($timestamp){
	return round(microtime(true)-$timestamp, 5);
}
function dbFile($file){
	if(file_exists($file)){
		$file = file($file);
		$data = array();
		$sep = '^';
		foreach ($file as $lineNum => $line){
			if(!empty($line)){
				if(strpos($line, $sep) !== false) $data[$lineNum] = explode($sep, trim($line));
				else $data[$lineNum] = trim($line);
			}
			else return false;
		}
		return $data;		
	}
	else error('localcache');
}
function implodeHuman($a){
	$last = array_pop($a); 
	if (!count($a)) return $last;
	return implode (', ', $a).' and '.$last; 
}
function deleteCookie($cookie){
	setcookie($cookie, '', time()-60*60*24*365, '/');
}

// errors	
$error = array(
	'404' => 'Invalid option.',
	'invalid' => 'The command <b>'.$command.'</b> is invalid.',
	'blocked' => 'Input did not pass security.',
	'notip' => 'Not a valid IP address.',
	'notdomain' => 'Illegal domain name.',
	'noreturn' => 'Host system did not respond to <b>'.$command.'</b>.',
	'auth' => 'You are not authorized to issue that command.',
	'timeout' => 'Request timed out. Please try again later.',
	'empty' => 'API is not responding.',
	'invalidxml' => 'API returned malformed XML.',
	'invalidjson' => 'API returned malformed JSON.',
	'invalidhtml' => 'API returned malformed HTML.',
	'localcache' => 'Local cache does not exist.',
	'password' => 'Incorrect password.'
);

// security and prompt
if(!empty($command)){
	$noReturn = array('msg', 'reply', 'sudo', 'yoda'); // these commands should not return input
	$pattern = "/^[[:alnum:][:space:]:.\,\'\'-?!\*+%]{0,160}$/";
	if(!empty($dump) && preg_match($pattern, $dump) || empty($dump)){
		if(!empty($option) and !in_array($command, $noReturn)) $prompt = '<div class="prompt">'.$command.' <b>'.$option.'</b></div>';
		else $prompt = '<div class="prompt">'.$command.'</div>';
	}
	else {
		$prompt = '<div class="prompt"></div>';
		error('blocked');
	}
}

// output
if(empty($output)) {
	// we need to load the brain
	include('brain.php');
	// quotes, bash
	if($command == "bash" || $command == "quote"){
		if($command == "bash") $array = $bash;
		elseif($command == "quote") $array = $quotes;
		$count = count($array)-1; $rand = rand(0,$count);
		if(!empty($option) && $option == "all"){
			foreach($array as $quote){
				if($command == "bash") $quote = '<div class="pre">'.$quote.'</div>';
				output($quote);
			}
		}
		elseif(isset($option) && $option == "clean"){
			print $array[$rand]; $output = 1;
		}
		else {
			$quote = $array[$rand];
			if($command == "bash") $quote = '<div class="pre">'.$quote.'</div>';
			output($quote);
		}
	}
	
	// motd 
	if($command == "motd"){
		$count = count($motd)-1; $rand = rand(0,$count);
		if(isset($option) && $option == "clean"){
			print '<p class="dark motd">'.$motd[$rand].'</p><p class="joshua">'.$joshua.'Please enter <span class="command">help</span> for commands.</p>'; $output = 1;
		}
		else {
			output($motd[$rand]);
		}
	}

	// uptime and date
	if($command == "uptime" || $command == "date"){
		$return = trim(exec($command));
		if(!empty($return))	output($return);
		else error('noreturn');
	}

	// whois
	if($command == "whois"){
		if(!empty($option)){
			$pattern = "/^[a-zA-Z0-9._-]+\.[a-zA-Z.]{2,4}$/";
			if (preg_match($pattern, $option)){
				$return = shell_exec('whois '.$option);
				if(!empty($return)){
					$return = utf8_encode($return);
					output('<pre>'.$return.'</pre>');
				}
				else error('noreturn');
			}
			else error('notdomain');
		}
		else output('<p class="error">'.$joshua.'You need to specify a domain name.</p><p class="example">whois binaerpilot.no</p>');
	}

	// prime number
	if($command == "prime"){
		if(!empty($option)){
			$i = 0; $unary = '';
			while($i++ < $option){
				$unary = $unary.'1';
			}
			$pattern = '/^1?$|^(11+?)\1+$/';
			if (preg_match($pattern, $unary)){
				output($option.' is not a prime number.');
			}
			else output($option.' is a prime number.');
		}
		else output('<p class="error">'.$joshua.'You need to specify a number.</p><p class="example">prime 13</p>');
	}

	// locate
	if($command == "locate"){
		$lookup = "http://api.hostip.info/get_html.php?position=true&ip=";
		if(!empty($option)) $ip = $option;
		else $ip = $_SERVER['REMOTE_ADDR'];
		$match = "/^[0-9]{2,3}\.[0-9]{2,3}\.[0-9]{2,3}\.[0-9]{2,3}$/";
		if(preg_match($pattern, $ip)){
			$request = $lookup.$ip;
			$output = get($request);
			// google maps link
			$latitude = trim(array_shift(explode('Longitude', array_pop(explode('Latitude:', $output)))));
			$longitude = trim(array_shift(explode('IP', array_pop(explode('Longitude:', $output)))));
			if(!empty($latitude) && !empty($longitude)) {
				output('<pre>'.$output.'</pre><p><a class="external" href="http://maps.google.com/maps?q='.$latitude.'+'.$longitude.'">View at Google Maps.</a></p>');
			}
			else output('<pre>'.$output.'</pre>');
		}
		else error('notip');
	}
	// numbers
	if($command == "numbers" || $command == "number" || $command == "n"){
		$levels = count($numbers);
		if(empty($_SESSION['numbers'])) $_SESSION['numbers'] = 0;
		if(isset($option) && $option == "reset"){
			unset($_SESSION['numbers']);
			output('<p class="joshua">'.$joshua.'Game reset.</p>');
		}
		else if($_SESSION['numbers'] == $levels){
			output('<p class="joshua">'.$joshua.'You have beaten the game! Use the code <b>idkfa</b> to list all the hidden commands.</p>');
		}
		else {
			if(empty($option)){
				$level = $_SESSION['numbers']+1;
				if($level != 1) output('<p>Level '.$level.': '.$numbers[$_SESSION['numbers']][0].'</p>');
				else output('<p>Level '.$level.': '.$numbers[$_SESSION['numbers']][0].'</p><p class="joshua">'.$joshua.'Type <b>number (x)</b> to answer the riddle.</p><p class="example">number 1</p>');
			}
			else {
				if($option == $numbers[$_SESSION['numbers']][1]){
					$_SESSION['numbers'] = $_SESSION['numbers']+1;
					$level = $_SESSION['numbers']+1;
					output('<p>Level '.$level.': '.$numbers[$_SESSION['numbers']][0].'</p>');
				}
				else output('<p class="error">'.$joshua.'Wrong answer. Try again.</p><p>'.$numbers[$_SESSION['numbers']][0].'</p>');
			}
		}
	}

	// msg
	if($command == "msg"){
		$storage = "msg.data";
		$message = trim(str_replace($command, '', $dump));
		if(strlen($message) > 0){
			if($option != "list" && $option != "listall"){
				if(strlen($message) < 10) $msgTooShort = true;
				else {
					if(!file_exists($storage)) touch($storage);
					$fp = fopen($storage, 'a');
					fwrite($fp, gmdate("d/m/y").'^'.$message.'^'.$_SERVER['REMOTE_ADDR']."\n");
					fclose($fp);					
				}
			}
			$db = dbFile($storage);
			$messages = array();
			foreach ($db as $entry => $message){
				$messages[$entry]['timestamp'] = $message[0];
				$messages[$entry]['message'] = $message[1];
				if(!empty($message[2])){
					$messages[$entry]['ip'] = $message[2];
				}
			}
			$messages = array_reverse($messages);
			$output = '<table class="fluid msg">';
			$limit = 20;
			if($option == "listall") $limit = count($messages);
			for ($i = 0; $i < $limit; $i++){
				if(isset($messages[$i]['ip'])) $output .= '<tr><td class="light">'.$messages[$i]['timestamp'].'</td><td>'.$messages[$i]['message'].'</td><td class="dark">'.$messages[$i]['ip'].'</td></tr>';
				else  $output .= '<tr><td class="light">'.$messages[$i]['timestamp'].'</td><td>'.$messages[$i]['message'].'</td><td></td></tr>';
			}
			$output .= '</table>';
			if(isset($msgTooShort)) output('<p class="error">'.$joshua.'Message is too short.</p>');
			else output($output);
		}
		else output('<p class="error">'.$joshua.'Message can\'t be empty.</p><p class="example">msg joshua needs more ultraviolence</p>');
	}

	// yoda
	if($command == "yoda"){
		$yodaPixel = '<div class="pixelPerson"><img src="images/iconYoda.png" width="27" height="28"></div>';
		$length = strlen($dump);
		if($length > 6	){
			$question = str_replace('yoda ','',$dump);
			if(!stristr($question, '?')) $question .= '?';
			$count = count($yoda)-1; $rand = rand(0,$count);
			print '<div class="prompt">'.$command.' <b>'.$question.'</b></div><div class="speechBubble">'.$yoda[$rand].'</div>'.$yodaPixel;
			$output = 1;
		}
		else output('<p class="speechBubble">Ask a question you must.</p>'.$yodaPixel);
	}

	// fml
	if($command == "fml"){
		$url = "http://feeds.feedburner.com/fmylife?format=xml";
		$cache = "fml.xml";
		get($url, $cache);
		$xml = load($cache);
		output($xml->entry[rand(0,9)]->content);
	}

	// cheat
	if($command == "idkfa"){
		foreach ($static as $key => $value) $commands[] .= $key;
		sort($commands); $commands = implodeHuman($commands);
		output($commands);
	}

	// lastfm
	if($command == "last.fm" || $command == "lastfm"){
		print $prompt.'<p>';
		if(!empty($option) && $option == "loved"){
			// loved tracks
			$url = 'http://ws.audioscrobbler.com/2.0/?method=user.getlovedtracks&user=astoever&api_key=a2b73335d53c05871eb50607e5df5466';
			$count = 10; $cache = 'lastfm.loved.xml';
			get($url, $cache, 1);
			$xml = load($cache, 1);
			for ($i = 0; $i < $count; $i++){
				$track = $xml->lovedtracks->track[$i]->name;
				$artist = $xml->lovedtracks->track[$i]->artist->name;
				print $artist.' - '.$track.'<br>'."\r";
			}	
		}
		else {
			// recent tracks
			$url = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=astoever&api_key=a2b73335d53c05871eb50607e5df5466';
			$count = 10; $cache = 'lastfm.xml';
			get($url, $cache, 1);
			$xml = load($cache, 1);
			for ($i = 0; $i < $count; $i++){
				$track = $xml->recenttracks->track[$i]->name;
				$artist =$xml->recenttracks->track[$i]->artist;
				print $artist.' - '.$track.'<br>'."\r";
			}
		}
		print '<a class="external" href="http://last.fm/user/astoever/" title="Alexander Støver on Last.FM">More useless data.</a></p>';
		$output = 1;	
	}

	// wtfig
	if($command == "wtfig" || $command == "figlet"){
		if(!isset($option)){
			output('<p class="error">'.$joshua.'You need to specify font and caption. See available fonts with <span class="command">wtfig list</span>.</p><p class="example">wtfig chunky Awesome!</p>');
		}
		else {
			if(file_exists("wtfig/fonts/$option.flf")){
				$font =  $option.'.flf';
				$caption = trim(str_replace($option, '', str_replace($command, '', $dump)));
				if(strlen($caption) > 0){
					// load class
					require("wtfig/class.figlet.php");
					$phpFiglet = new phpFiglet();
					if ($phpFiglet->loadFont("wtfig/fonts/".$font)){
						$wtFIG = $phpFiglet->fetch($caption);
						output('<pre class="ascii">'.$wtFIG.'</pre>');
					}
				}
				else {
					output('<p class="error">'.$joshua.'You didn\'t write anything to figletize.</p>');
				}
			}
			else {
				$fontList = array();
				$dir = scandir("wtfig/fonts/");
				foreach($dir as $file){
					if(strpos($file,".flf")){
						$fontName = str_replace('.flf', '', $file);
						$fontList[] = $fontName;
					}
				}
				sort($fontList); $fonts = implodeHuman($fontList);
				$output = '<p>'.$fonts.'.</p>';
				if($option != "list"){
					$output = '<p class="error">'.$joshua.'Invalid font. See list below.</p>'.$output;
				}
				output($output);
			}
		}
	}

	// get (torrents)
	if($command == "get" || $command == "torrents"){
		if(isset($option)){
			$rows = 20; $query = str_replace($command.' ', '', $dump);
			$url = 'http://ca.isohunt.com/js/json.php?ihq='.urlencode($query).'&start=0&rows='.$rows.'&sort=seeds';
			$content = get($url);
			if($content){
				print '<div class="prompt">'.$command.' <b>'.$query.'</b></div>';
				$c = json_decode($content, true);
				if($c['total_results'] > 0){
					print '<table class="torrents">';
					for ($i = 0; $i < $rows; $i++){
						$name = $c['items']['list'][$i]['title'];
						$link = $c['items']['list'][$i]['link'];
						$size = $c['items']['list'][$i]['size'];
						$seeds = $c['items']['list'][$i]['Seeds'];
						$leechers = $c['items']['list'][$i]['leechers'];
						$title = $name.' ('.$size.')';
						if(strlen($name) > 83) $name = substr($name, 0, 80).'...';
						if(!empty($link) && !empty($seeds)){
							print '<tr><td class="torrent"><a href="'.$link.'" title="'.$title.'">'.$name.'</a></td><td class="dark">'.$seeds.'/'.$leechers.'</td></tr>';
						}
					}
					print '</table>'; $output = 1;
				}
				else output('<p class="error">'.$joshua.'There were no results for <b>'.$query.'</b>.</p>');
			}
			else {
				error('timeout');
			}
		}
		else output('<p class="error">'.$joshua.'You need to specify something to look for.</p><p class="example">get binaerpilot</p>');
	}

	// themes
	if($command == "theme" || $command == "themes"){
		$themes = array();
		if(isset($_COOKIE['konami'])) $themes[] = 'contra';
		foreach(scandir("themes") as $file){
			if(stristr($file, '.css')){
				$theme = str_replace('.css', '', $file);
				if($theme != "contra") $themes[] = $theme;
			}
		}
		sort($themes);
		if(isset($option) && in_array($option, $themes)){
			setcookie('theme', $option, $expires, '/');
			output('<p class="joshua">'.$joshua.'Your browser will now refresh automatically.</p><script>location.reload();</script>');
		}
		else output('<p class="error">'.$joshua.'Valid options are '.implodeHuman($themes).'.</p><p class="example">'.$command.' '.$themes[rand(0,count($themes)-1)].'</p>');
	}

	// presets
	if($command == "preset" || $command == "presets"){
		$presets = array('rachael', 'gamer', 'tron');
		sort($presets);
		if(isset($option) && in_array($option, $presets)){
			if($option == "gamer"){
				setcookie('theme', 'carolla', $expires, '/');
				setcookie('background', 'atari', $expires, '/');
				setcookie('fx', 'sparks', $expires, '/');
				deleteCookie('opacity');
			}
			else if($option == "rachael"){
				setcookie('theme', 'penguin', $expires, '/');
				setcookie('background', 'rachael', $expires, '/');
				deleteCookie('fx');
				deleteCookie('opacity');
			}
			else if($option == "tron"){
				setcookie('theme', 'tron', $expires, '/');
				deleteCookie('background');
				setcookie('fx', 'sparks', $expires, '/');
				deleteCookie('opacity');
				setcookie('tron.team', 'pink', $expires, '/');
			}
			output('<meta http-equiv="refresh" content="0">');
		}
		else output('<p class="error">'.$joshua.'Valid options are '.implodeHuman($presets).'.</p><p class="example">'.$command.' '.$presets[rand(0,count($presets)-1)].'</p>');
	}

	// superplastic
	if($command == "superplastic"){
		if(!empty($_POST['name'])) $name = strip_tags(trim($_POST['name']));
		if(!empty($_POST['score'])) $score = strip_tags(trim($_POST['score']));
		$storage = "superplastic.data";
		if(!empty($name) && !empty($score)){
			if(!file_exists($storage)) touch($storage);
			$fp = fopen($storage, 'a');
			fwrite($fp, $score.'^'.$name."\n");
			fclose($fp);
		}
		$db = dbFile($storage);
		$scores = array();
		foreach ($db as $entry => $score){
			$scores[$entry]['score'] = $score[0];
			$scores[$entry]['name'] = $score[1];
		}
		rsort($scores);
		print '<h2>Season V Highscores</h2><ul>';
		for ($i = 0; $i<30; $i++){
			$pos = $i+1;
			if($pos < 10) $pos = '0'.$pos;
			print '<li><span class="pos">'.$pos.'.</span><b>'.$scores[$i]['name'].'</b> <span class="score">'.$scores[$i]['score'].'</span></li>';
		}
		print '</ul>';$output = 1;
	}

	// calc
	if($command == "calc"){
		if(isset($option)) {
			if(preg_match('/^([0-9]+[+-\/*%][0-9]+)*$/', $option)){
				$return = shell_exec("awk 'BEGIN {print $option}'");
				if(!empty($return)){
					output($return);
				}
				else error('noreturn');
			}
			else output('<p class="error">'.$joshua.'Does not compute.</p>');
		}
		else output('<p class="error">'.$joshua.'There\'s nothing to calculate.</p><p class="example">calc 6*9</p>');
	}
	
	// md5
	if($command == "md5"){
		if(isset($option)) {
			output('<p>'.md5($option).'</p>');
		}
		else output('<p class="error">'.$joshua.'You need to specify a string.</p><p class="example">md5 joshua</p>');
		
	}

	// reviews
	if($command == "reviews" || $command == "review" || $command == "r"){
		if(empty($option)) {
			print $prompt.'<p>One day we had a great idea: '.
				'"Let\'s watch all the worst movies in the world!"</i><br> '.
				'In retrospect, it might not have been the greatest of ideas. ';
			print '<table class="reviews fluid">';
			foreach ($reviews as $key => $value){
				print '<tr><td class="light">'.($key+1).'</td><td>'.$value['title'].' ('.$value['year'].')</td><td class="dark">'.$value['rating'].'/10</td></tr>';
			}
			print '</table>';
			print '<p class="joshua">'.$joshua.'Type <b>review (x)</b> to read a review.</p><p class="example">review '.rand(0,count($reviews)-1).'</p>';
			$output = 1;
		}
		else {
			$pattern = "/^[0-9]+$/";
			if(preg_match($pattern, $option)){
				$id = $option-1;
				if(!empty($reviews[$id])){
					print $prompt.'<p><b>'.$reviews[$id]['title'].'</b> ('.$reviews[$id]['year'].') <span class="dark">'.$reviews[$id]['rating'].'/10</span></p>'.
						$reviews[$id]['review'].
						'<p><a class="external" href="http://www.imdb.com/find?s=all;q='.urlencode($reviews[$id]['title'].' '.$reviews[$id]['year']).'">View movie on IMDb.</a></p>';
					$output = 1;
				}
				else error("404");
			}
			else error("blocked");
		}
	}

	// stats
	if($command == "stats"){
		$timestamp = microtime(true);
		$brainCells = 0; $themes = 0; $bytes = 0; $lines = 0;
		$dir = '.'; $scan = scandir($dir);
		foreach ($scan as $file){
			if(!stristr($file, '.xml') && !stristr($file, '.data') && !is_dir($file)){
				$bytes = $bytes + filesize($file);
				$lines = $lines + count(file($file));
			}
			if(stristr($file, 'cell.'))	$brainCells = $brainCells+1;
			else if(stristr($file, '.xml')) $brainCells = $brainCells+1;
		}
		$dir = 'themes/'; $scan = scandir($dir);
		foreach ($scan as $file){
			if(!is_dir($file)){
				$bytes = $bytes + filesize($dir.$file);
				$lines = $lines + count(file($dir.$file));
			}
			if(stristr($file, '.css')) $themes = $themes+1;
		}
		if(file_exists('msg.data')) $messages = count(explode("\n", file_get_contents('msg.data')));
		if(file_exists('superplastic.data')) $scores = count(explode("\n", file_get_contents('superplastic.data')))+2828; // from season 1-4
		$commands = count($static)+30; // guesstimate
		$quotes = count($motd)+count($bash)+count($quotes);
		$reviews = count($reviews);
		$stats = '<table class="stats">'.
			'<tr><td class="light">Commands</td><td>'.$commands.'</td><td class="dark">Yes, there are at least that many</td></tr>'.
			'<tr><td class="light">Brain cells</td><td>'.$brainCells.'</td><td class="dark">All external files loaded by the brain</td></tr>'.
			'<tr><td class="light">Themes</td><td>'.$themes.'</td><td class="dark">Some themes have to be unlocked...</td></tr>'.
			'<tr><td class="light">Bytes</td><td>'.$bytes.'</td><td class="dark">Everything hand-coded with Notepad++ and TextMate</td></tr>'.
			'<tr><td class="light">Lines</td><td>'.$lines.'</td><td class="dark">Lines of code (no externals)</td></tr>'.
			'<tr><td class="light">Messages</td><td>'.$messages.'</td><td class="dark">Left with the msg command</td></tr>'.
			'<tr><td class="light">Reviews</td><td>'.$reviews.'</td><td class="dark">Reviews of terrible movies</td></tr>'.
			'<tr><td class="light">Scores</td><td>'.$scores.'</td><td class="dark">Superplastic record attempts</td></tr>'.
			'<tr><td class="light">Quotes</td><td>'.$quotes.'</td><td class="dark">Includes MOTD\'s and bash.org quotes</td></tr>'.
			'<tr><td class="light">Timer</td><td>'.microtimer($timestamp).'</td><td class="dark">The seconds it took to compile these stats</td></tr>'.
			'</table>';
		output($stats);
	}

	// hi reddit
	if($command == "let's" || $command == "lets" || $command == "how"){
		$wargames = array("let's play global thermonuclear war", "lets play global thermonuclear war", "how about global thermonuclear war", "how about global thermonuclear war?");
		if(in_array(strtolower($dump), $wargames)){
			$prompt = '<div class="prompt">'.$dump.'</div>';
			output('<p class="joshua">'.$joshua.'Wouldn\'t you prefer a nice game of chess?</p>');
			
		}
	}
	
	// fallback
	if(empty($output)){
		foreach ($static as $key => $value){
			if ($key == $command) output($value);
		}
		if(empty($output)){
			// lets store commands that fail and populate them
			$storage = "invalid.data";
			if(!file_exists($storage)) touch($storage);
			$fp = fopen($storage, 'a');
			fwrite($fp, $dump."\n");
			fclose($fp);
			// feedback
			error('invalid');
		}
	}
}

?>