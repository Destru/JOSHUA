<?php
include 'inc.global.php';
include 'inc.keys.php';
if (!empty($_POST['command'])) $command = strtolower(userInput($_POST['command']));
if (!empty($_POST['option'])) $option = userInput($_POST['option']);
if (!empty($_POST['dump'])) $dump = userInput($_POST['dump']);
if (!empty($option) && $option == "undefined") unset($option);
if (!empty($dump) && $dump == "undefined") unset($dump);
if (isset($command) && isset($dump)) {
	$pos = strpos($dump, $command);
	if ($pos !== false) $input = trim(substr_replace($dump, '', $pos, strlen($command)));
}
if (empty($input)) unset($input);
unset($output);

function error($id, $inline=null) {
	global $error, $command, $prompt, $joshua;
	if (!$inline) print $prompt;
	print '<p class="joshua error">'.$joshua.$error[$id];
	die();
}

function output($response) {
	global $output, $command, $option, $input, $prompt;
	if (stristr($response,'<p') || stristr($response,'<table')|| stristr($response,'<ul')) print $prompt.$response;
	else print $prompt.'<p>'.$response;
	$output = true;
}

function run($cmd, $opt=null) {
	$timeout = 10;
	return trim(utf8_encode(shell_exec($cmd.' '.$opt)));
}

function microtimer($timestamp) {
	return round(microtime(true)-$timestamp, 5);
}

function dbFile($file) {
	if (file_exists($file)) {
		$file = file($file);
		$data = array();
		$sep = '^';
		foreach ($file as $lineNum => $line) {
			if (!empty($line)) {
				if (strpos($line, $sep) !== false) $data[$lineNum] = explode($sep, trim($line));
				else $data[$lineNum] = trim($line);
			}
			else return false;
		}
		return $data;
	}
	else error('localcache');
}

function implodeHuman($a, $command=false) {
	$last = array_pop($a);
	if ($command) {
		if (!count($a)) return '<span class="command">'.$last.'</span>';
		return '<span class="command">'.implode('</span>, <span class="command">', $a).'</span> and <span class="command">'.$last.'</span>';
	}
	else {
		if (!count($a)) return $last;
		return implode(', ', $a).' and '.$last;
	}
}

function deleteCookie($cookie) {
	setcookie($cookie, '', time()-60*60*24*365, '/');
}

function deCamel($s) {
	return ucfirst(preg_replace( '/([a-z0-9])([A-Z])/', "$1 $2", $s));
}

function cakeDay($date) {
	$cake = (strtotime(date("Ymd")) > strtotime(date("Y/$date"))) ? strtotime(date("Y/$date", strtotime("+1 year"))) : strtotime(date("Y/$date"));
	return round(($cake-strtotime(date("Ymd")))/86400);
}

// errors
$error = array(
	'404' => 'I couldn\'t find that.',
	'invalid' => 'I don\'t understand. Do you need some <span class="command">help</span>?',
	'blocked' => 'Invalid input. This shouldn\'t get triggered anymore so not sure what you\'re doing.',
	'notip' => 'That\'s not an IP. Regex never lies.',
	'notdomain' => 'You call that a domain name?',
	'noreturn' => 'Executing <span class="command">'.$command.'</span> on this system returned nothing. I am disappoint.',
	'timeout' => 'I\'m asking ever so nicely but the server did not respond.',
	'empty' => 'That API is not responding. Or I have been throttled. Either way it sucks.',
	'invalidxml' => 'API returned malformed XML. XML sucks.',
	'invalidjson' => 'API returned malformed JSON. How can you mess up JSON?',
	'invalidhtml' => 'API returned malformed HTML. Is there even such a thing as well-formed HTML?',
	'invalidrequest' => 'API threw an error. This is bad and I should feel bad.',
	'localcache' => 'Local cache does not exist. IT\'S GONE! ALL GONE!',
	'auth' => 'You are not authorized to issue that command.',
	'password' => 'Wrong password.',
	'outdatedapi' => 'API returned something, but not what I expected. Probably needs an update.'
);

// prompt
if (!empty($command)) {
	$noReturn = array('sudo');
	if (!empty($input) and !in_array($command, $noReturn)) {
		$prompt = '<div class="prompt">'.$command.' <b>'.$input.'</b></div>';
	}
	else $prompt = '<div class="prompt">'.$command.'</div>';
}

// output
if (empty($output)) {
	include('brain.php');

	// quotes and bash
	if ($command == "bash" || $command == "quote" || $command == "quotes") {
		if ($command == "bash") $array = $bash;
		elseif ($command == "quote" || $command == "quotes") $array = $quotes;
		$count = count($array)-1; $rand = rand(0,$count);
		if (!empty($option) && $option == "all") {
			foreach($array as $quote) {
				if ($command == "bash") $quote = '<p class="irc">'.$quote;
				output($quote);
			}
		}
		elseif (isset($option) && $option == "clean") {
			print $array[$rand];
			$output = true;
		}
		else {
			$quote = $array[$rand];
			if ($command == "bash") $quote = '<p class="irc">'.$quote;
			output($quote);
		}
	}

	// motd
	if ($command == "motd") {
		$count = count($motd)-1; $rand = rand(0,$count);
		if (isset($option) && $option == "inline") {
			print '<p class="dark motd">'.$motd[$rand].'<p class="joshua">'.$joshua.'Please enter <span class="command">help</span> for commands.';
			$output = true;
		}
		else {
			output($motd[$rand]);
		}
	}

	// uptime and date
	if ($command == "uptime" || $command == "date") {
		$return = run($command);
		if (!empty($return)) output($return);
		else error('noreturn');
	}

	// whois and ping
	if ($command == "whois" || $command == "ping") {
		if (isset($option)) {
			$pattern = "/^[a-zA-Z0-9._-]+\.[a-zA-Z.]{2,4}$/";
			if (preg_match($pattern, $option)) {
				if ($command == "ping") {
					$return = run('ping', '-c1 '.$option);
				}
				elseif ($command == "whois") {
					$return = run('whois', $option);
				}
				if (!empty($return)) {
					output('<pre>'.$return.'</pre>');
				}
				else error('noreturn');
			}
			else error('notdomain');
		}
		else output('<p class="error">'.$joshua.'I need a domain to lookup.<p class="example">'.$command.' binaerpilot.no');
	}

	// prime
	if ($command == "prime") {
		if (!empty($option)) {
			if (strlen($option) <= 5) {
				$i = 0; $unary = '';
				while($i++ < $option) {
					$unary = $unary.'1';
				}
				$pattern = '/^1?$|^(11+?)\1+$/';
				if (preg_match($pattern, $unary)) {
					output($option.' is not a prime number.');
				}
				else output($option.' is a prime number.');
			}
			else output('<p class="error">'.$joshua.'The number is too damn high (for regex).');
		}
		else output('<p class="error">'.$joshua.'You have to tell me a number.<p class="example">prime 13');
	}

	// locate
	if ($command == "locate") {
		// TODO: update to http://ipinfodb.com/ip_location_api_json.php
		$lookup = 'http://api.hostip.info/get_html.php?position=true&ip=';
		if (!empty($option)) $ip = $option;
		else $ip = $_SERVER['REMOTE_ADDR'];
		if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
			$request = $lookup.$ip;
			$output = get($request);
			$latitude = trim(array_shift(explode('Longitude', array_pop(explode('Latitude:', $output)))));
			$longitude = trim(array_shift(explode('IP', array_pop(explode('Longitude:', $output)))));
			if (!empty($latitude) && !empty($longitude)) {
				output('<pre>'.$output.'</pre><p><a class="external" href="http://maps.google.com/maps?q='.$latitude.'+'.$longitude.'">View at Google Maps.</a>');
			}
			else output('<pre>'.$output.'</pre>');
		}
		else error('notip');
	}

	// numbers
	if ($command == "numbers" || $command == "number") {
		$levels = count($numbers);
		if (empty($_SESSION['numbers'])) $_SESSION['numbers'] = 0;
		if (isset($option) && $option == "reset") {
			unset($_SESSION['numbers']);
			output('<p class="joshua">'.$joshua.'Game reset.');
		}
		else if ($_SESSION['numbers'] == $levels) {
			output('<p class="joshua">'.$joshua.'You have beaten the game! Use <span class="command">idkfa</span> to list all the static keys.');
		}
		else {
			if (empty($option)) {
				$level = $_SESSION['numbers']+1;
				if ($level != 1) output('<p>Level '.$level.': '.$numbers[$_SESSION['numbers']][0]);
				else output('<p>Level '.$level.': '.$numbers[$_SESSION['numbers']][0].'<p class="error">'.$joshua.'Type <b>number (x)</b> to answer the riddle.<p class="example">number 1');
			}
			else {
				if ($option == $numbers[$_SESSION['numbers']][1]) {
					$_SESSION['numbers'] = $_SESSION['numbers']+1;
					$level = $_SESSION['numbers']+1;
					output('<p>Level '.$level.': '.$numbers[$_SESSION['numbers']][0]);
				}
				else output('<p class="error">'.$joshua.'Wrong answer. Try again.<p>'.$numbers[$_SESSION['numbers']][0]);
			}
		}
	}

	// msg
	if ($command == "msg") {
		$storage = "msg.data";
		if (isset($input)) {
			if ($option != "list" && $option != "listall") {
				if (strlen($input) < 10) $msgTooShort = true;
				else if (trim($input) == "joshua needs more ultraviolence") $msgExample = true;
				else {
					if (!file_exists($storage)) touch($storage);
					$fp = fopen($storage, 'a');
					fwrite($fp, gmdate("d/m/y").'^'.$input.'^'.$_SERVER['REMOTE_ADDR']."\n");
					fclose($fp);
				}
			}
			$db = dbFile($storage);
			$messages = array();
			foreach ($db as $entry => $message) {
				$messages[$entry]['timestamp'] = $message[0];
				$messages[$entry]['message'] = $message[1];
				if (!empty($message[2])) {
					$messages[$entry]['ip'] = $message[2];
				}
			}
			$messages = array_reverse($messages);
			$output = '<table class="fluid msg">';
			$limit = 20;
			if ($option == "listall") $limit = count($messages);
			for ($i = 0; $i < $limit; $i++) {
				if (isset($messages[$i])) {
					$output .= '<tr><td class="dark fixed-width">'.$messages[$i]['timestamp'].'</td><td>'.stripslashes($messages[$i]['message']).'</td><td></td></tr>';
				}
			}
			$output .= '</table>';
			if (isset($msgTooShort)) output('<p class="error">'.$joshua.'Message is too short.');
			else if (isset($msgExample)) output('<p class="joshua">'.$joshua.'Yes, that is how it works, and this message is an example.');
			else output($output);
		}
		else output('<p class="error">'.$joshua.'Message can\'t be empty.<p class="example">msg joshua needs more ultraviolence');
	}

	// yoda
	if ($command == "yoda") {
		$yoda = '<div class="yoda"><img src="images/iconYoda.png" width="27" height="28"></div>';
		if (isset($input)) {
			$count = count($yodaQuotes)-1; $rand = rand(0,$count);
			output('<div class="speechBubble">'.$yodaQuotes[$rand].'</div>'.$yoda);
		}
		else output('<p class="speechBubble">Ask a question you must.'.$yoda);
	}

	// fml
	if ($command == "fml") {
		$url = "http://feedpress.me/fmylife";
		$cache = "fml.xml";
		get($url, $cache);
		$xml = load($cache);
		output($xml->entry[rand(0,9)]->content);
	}

	// cheat
	if ($command == "idkfa") {
		foreach ($static as $key => $value) $commands[] .= $key;
		sort($commands); $commands = implodeHuman($commands, true);
		output($commands);
	}

	// lastfm
	if ($command == "last.fm" || $command == "lastfm") {
		$output = '';
		if (!empty($option) && $option == "loved") {
			$url = 'http://ws.audioscrobbler.com/2.0/?method=user.getlovedtracks&user=astoever&api_key='.$keys['lastfm'];
			$count = 10; $cache = 'lastfm.loved.xml';
			get($url, $cache);
			$xml = load($cache);
			for ($i = 0; $i < $count; $i++) {
				$track = $xml->lovedtracks->track[$i]->name;
				$artist = $xml->lovedtracks->track[$i]->artist->name;
				$output .= $artist.' - '.$track.'<br>'."\r";
			}
		}
		else {
			$url = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=astoever&api_key='.$keys['lastfm'];
			$count = 10; $cache = 'lastfm.xml';
			get($url, $cache);
			$xml = load($cache);
			for ($i = 0; $i < $count; $i++) {
				$track = $xml->recenttracks->track[$i]->name;
				$artist =$xml->recenttracks->track[$i]->artist;
				$output .= $artist.' - '.$track.'<br>'."\r";
			}
		}
		$output .= '<br><a class="external" href="http://last.fm/user/astoever/" title="Alexander Støver on Last.FM">More useless data.</a>';
		output($output);
	}

	// get
	if ($command == "get" || $command == "torrent" || $command == "magnet") {
		if (isset($option)) {
			$host = 'https://torrentproject.se/';
			$query = '?s='.urlencode($input).'&out=json';
			$content = get($host.$query);
			if ($content) {
				$torrents = json_decode($content, true);
				if (count($torrents)) {
					print $prompt.
						'<table class="torrents">';
					foreach($torrents as $i) {
						if (is_array($i)) {
							$title = $i['title'];
							$hash = $i['torrent_hash'];
							$size = humanFileSize($i['torrent_size']);
							$seeders = $i['seeds'];
							$leechers = $i['leechs'];
							$trackers = '&tr=http://bt01.gamebar.com:6969/announce'.
								'&tr=http://mgtracker.org:2710/announce'.
								'&tr=http://tracker.blucds.com:2710/announce'.
								'&tr=udp://open.demonii.com:1337/announce'.
								'&tr=udp://tracker.coppersurfer.tk:6969/announce'.
								'&tr=udp://tracker.leechers-paradise.org:6969/announce';
							$link = 'magnet:?xt=urn:btih:'.$hash.'&dn='.$title.$trackers;
							print '<tr>'.
								'<td class="torrent"><a href="'.$link.'">'.substr($title, 0, 80).'</a></td>'.
								'<td>'.$seeders.'/'.$leechers.'</td>'.
								'<td class="dark">'.$size.'</td>'.
								'</tr>';
						}
					}
					print '</table>';
					$output = true;
				}
				else output('<p class="error">'.$joshua.'<b>'.$input.'</b> returned nothing.');
			}
			else {
				error('timeout');
			}
		}
		else output('<p class="error">'.$joshua.'You need to tell me something to look for.<p class="example">get binaerpilot');
	}

	// themes
	if ($command == "theme" || $command == "themes") {
		$themes = array();
		if (isset($_COOKIE['konami'])) $themes[] = 'contra';
		foreach(scandir("themes") as $file) {
			if (stristr($file, '.css')) {
				$theme = str_replace('.css', '', $file);
				if ($theme != "contra") $themes[] = $theme;
			}
		}
		sort($themes);
		if (isset($option) && in_array($option, $themes)) {
			setcookie('theme', $option, $cookieExpires, '/');
			output('<script>location.reload();</script>');
		}
		else if (isset($option) && $option == "random") {
			setcookie('theme', $themes[rand(0,count($themes)-1)], $cookieExpires, '/');
			output('<script>location.reload();</script>');
		}
		else output('<p class="error">'.$joshua.'Choose between '.implodeHuman($themes).'.<p class="example">'.$command.' random');
	}

	// presets
	if ($command == "preset" || $command == "presets") {
		$presets = array('gamer', 'rachael', 'prometheus');
		sort($presets);
		if (isset($option) && in_array($option, $presets)) {
			if ($option == "gamer") {
				setcookie('theme', 'carolla', $cookieExpires, '/');
				setcookie('background', 'clg', $cookieExpires, '/');
				setcookie('fx', 'sparks', $cookieExpires, '/');
				deleteCookie('opacity');
				deleteCookie('hue');
				deleteCookie('saturation');
			}
			else if ($option == "rachael") {
				setcookie('theme', 'rachael', $cookieExpires, '/');
				setcookie('background', 'rachael', $cookieExpires, '/');
				deleteCookie('fx');
				deleteCookie('opacity');
				deleteCookie('hue');
				deleteCookie('saturation');
			}
			else if ($option == "prometheus") {
				setcookie('theme', 'mono', $cookieExpires, '/');
				setcookie('fx', 'pulsar', $cookieExpires, '/');
			}
			output('<script>location.reload();</script>');
		}
		else output('<p class="error">'.$joshua.'Choose between '.implodeHuman($presets).'.<p class="example">'.$command.' '.$presets[rand(0,count($presets)-1)]);
	}

	// scores (superplastic)
	if ($command == "scores") {
		if (!empty($_POST['name'])) $name = userInput($_POST['name']);
		if (!empty($_POST['score'])) $score = userInput($_POST['score']);
		if (!is_numeric($score)) unset($score);
		$storage = "superplastic.data";
		if (!empty($name) && !empty($score)) {
			if (!file_exists($storage)) touch($storage);
			$fp = fopen($storage, 'a');
			fwrite($fp, $score.'^'.$name."\n");
			fclose($fp);
		}
		$db = dbFile($storage);
		$scores = array();
		foreach ($db as $entry => $score) {
			$scores[$entry]['score'] = $score[0];
			$scores[$entry]['name'] = $score[1];
		}
		rsort($scores);
		print '<ul>';
		for ($i = 0; $i<30; $i++) {
			$pos = $i+1;
			if ($pos < 10) $pos = '0'.$pos;
			print '<li><span class="pos">'.$pos.'.</span><b>'.$scores[$i]['name'].'</b> <span class="score">'.$scores[$i]['score'].'</span></li>';
		}
		print '</ul>';
		$output = true;
	}

	// calc
	if ($command == "calc") {
		if (isset($option)) {
			if (preg_match('/^([0-9]+[+-\/*%][0-9]+)*$/', $option)) {
				if ($option == "6*9") $return = 42;
				else $return = shell_exec("awk 'BEGIN {print $option}'");
				if (!empty($return)) {
					output($return);
				}
				else error('noreturn');
			}
			else output('<p class="error">'.$joshua.'Does not compute.');
		}
		else output('<p class="error">'.$joshua.'There\'s nothing to calculate.<p class="example">calc 6*9');
	}

	// hash
	if ($command == "hash" || $command == "md5" || $command == "sha1") {
		$example = '<p class="example">hash md5 joshua';
		if ($command == "md5" || $command == "sha1") $option = $command;
		if (isset($option)) {
			$string = trim(str_replace($option, '', $input));
			if (in_array($option, hash_algos())) {
				if (strlen($string) > 0) {
					output('<p>'.hash($option, $string));
				}
				else {
					output('<p class="error">'.$joshua.'You need to specify both an algorithm and a string.'.$example);
				}
			}
			else {
				output('<p class="error">'.$joshua.'Valid options are '.implodeHuman(hash_algos()).'.'.$example);
			}
		}
		else output('<p class="error">'.$joshua.'Can\'t hash an empty string.'.$example);
	}

	// reviews
	if ($command == "reviews" || $command == "review" || $command == "r") {
		if (empty($option)) {
			print $prompt.'<p>One day we had a great idea: '.
				'"Let\'s watch all the worst movies in the world!"</i><br> '.
				'In retrospect, it might not have been the greatest of ideas. ';
			print '<table class="reviews fluid">';
			foreach ($reviews as $key => $value) {
				print '<tr><td><span class="command">review '.($key+1).'</span></td><td>'.$value['title'].'</td><td class="dark">'.$value['year'].'</td><td class="light">'.$value['rating'].'/10</td></tr>';
			}
			print '</table>';
			print '<p class="error">'.$joshua.'Type <b>review (x)</b> to read a review.<p class="example">review '.rand(0,count($reviews)-1);
			$output = true;
		}
		else {
			$pattern = "/^[0-9]+$/";
			if (preg_match($pattern, $option)) {
				$id = $option-1;
				if (!empty($reviews[$id])) {
					print $prompt.'<p><b class="light">'.$reviews[$id]['title'].'</b> <span class="dark">('.$reviews[$id]['year'].')</span> '.$reviews[$id]['rating'].'/10'.
						$reviews[$id]['review'].
						'<p><a class="external" href="http://www.imdb.com/find?s=all;q='.urlencode($reviews[$id]['title'].' '.$reviews[$id]['year']).'">View movie on IMDb.</a>';
					$output = true;
				}
				else error("404");
			}
			else error("blocked");
		}
	}

	// stats
	if ($command == "stats") {
		$timestamp = microtime(true);
		$brainCells = 0; $themes = 0; $bytes = 0; $lines = 0;
		$dir = '.'; $scan = scandir($dir);
		foreach ($scan as $file) {
			if (!stristr($file, '.xml') && !stristr($file, '.data') && !is_dir($file)) {
				$bytes = $bytes + filesize($file);
				$lines = $lines + count(file($file));
			}
			if (stristr($file, 'cell.')) $brainCells = $brainCells+1;
			else if (stristr($file, '.xml')) $brainCells = $brainCells+1;
		}
		$dir = 'themes/'; $scan = scandir($dir);
		foreach ($scan as $file) {
			if (!is_dir($file)) {
				$bytes = $bytes + filesize($dir.$file);
				$lines = $lines + count(file($dir.$file));
			}
			if (stristr($file, '.css')) $themes = $themes+1;
		}
		if (file_exists('msg.data')) $messages = count(explode("\n", file_get_contents('msg.data')));
		else $messages = 0;
		if (file_exists('superplastic.data')) $scores = count(explode("\n", file_get_contents('superplastic.data')))+5000;
		else $scores = 0;
		$commands = count($static)+30; // guesstimate
		$quotes = count($motd)+count($bash)+count($quotes);
		$reviews = count($reviews);
		$stats = '<table class="stats">'.
			'<tr><td>Commands</td><td class="light">'.$commands.'</td><td class="dark">Yes, there are at least that many</td></tr>'.
			'<tr><td>Brain cells</td><td class="light">'.$brainCells.'</td><td class="dark">All external files loaded by the brain</td></tr>'.
			'<tr><td>Themes</td><td class="light">'.$themes.'</td><td class="dark">Some themes have to be unlocked...</td></tr>'.
			'<tr><td>Bytes</td><td class="light">'.$bytes.'</td><td class="dark">Hand-coded with Notepad++, TextMate and Sublime</td></tr>'.
			'<tr><td>Lines</td><td class="light">'.$lines.'</td><td class="dark">Lines of code (no externals)</td></tr>'.
			'<tr><td>Messages</td><td class="light">'.$messages.'</td><td class="dark">Left with the msg command</td></tr>'.
			'<tr><td>Reviews</td><td class="light">'.$reviews.'</td><td class="dark">Reviews of terrible movies</td></tr>'.
			'<tr><td>Scores</td><td class="light">'.$scores.'</td><td class="dark">Superplastic record attempts</td></tr>'.
			'<tr><td>Quotes</td><td class="light">'.$quotes.'</td><td class="dark">Includes MOTD\'s and bash.org quotes</td></tr>'.
			'<tr><td>Timer</td><td class="light">'.microtimer($timestamp).'</td><td class="dark">The seconds it took to compile these stats</td></tr>'.
			'</table>';
		output($stats);
	}

	// hi reddit
	if ($command == "let\'s" || $command == "lets" || $command == "how") {
		if (preg_match('/thermonuclear/i', $dump)) {
			$prompt = '<div class="prompt">how about global thermonuclear war?</div>';
			output('<p class="joshua">'.$joshua.'Wouldn\'t you prefer a nice game of chess?');
		}
	}

	// say
	if ($command == "say" || $command == "talk" || $command == "speak") {
		if (isset($input)) {
			print '<div class="prompt">'.$command.' <b>'.$input.'</b></div>'.
				'<script>speak(\''.$input.'\', { pitch:50, speed:120 });</script>';
			$output = true;
		}
		else output('<p class="error">'.$joshua.'What do you want me to say?<p class="example">say hello');
	}

	// rate
	if ($command == "rate" || $command == "rating") {
		if (isset($input)) {
			$omdb = 'http://www.omdbapi.com/?t='.urlencode($input).'&tomatoes=true';
			$omdb = json_decode(get($omdb));
			if (filter_var($omdb->Response, FILTER_VALIDATE_BOOLEAN)) {
				print $prompt.
					'<p><b class="light">'.$omdb->Title.'</b> <span class="dark">('.$omdb->Year.')</span><br>'.
					'<span class="dark">'.$omdb->Genre.'</span>';
				if ($omdb->Plot != "N/A") print '<p>'.$omdb->Plot;
				print '<table class="fluid rate">'.
						'<tr><td>IMDb</td><td class="light">'.$omdb->imdbRating.'</td></tr>'.
						'<tr><td>Tomatometer</td><td class="light">'.$omdb->tomatoMeter.'</td></tr>'.
						'<tr><td>Metascore</td><td class="light">'.$omdb->Metascore.'</td></tr>'.
					'</table>';
				if ($omdb->tomatoConsensus != "N/A") print '<p class="dark">'.$omdb->tomatoConsensus;
				print '<p><span class="command">get '.strtolower($omdb->Title).'</span>';
				$output = true;
			}
			else error('404');
		}
		else output('<p class="error">'.$joshua.'What am I looking for?<p class="example">'.$command.' blade runner');
	}

	// img
	if ($command == "img" || $command == "image" || $command == "images") {
		if (isset($input)) {
			$tag = str_replace(' ','', $input);
			$instagram = 'https://api.instagram.com/v1/tags/'.$tag.'/media/recent?client_id='.$keys['instagram'].'&count=30';
			$instagram = get($instagram);
			if ($instagram) {
				$output = '';
				$result = json_decode($instagram);
				if ($result->data) {
					$resultCount = count($result->data);
					$thumbnails = ($resultCount > 18) ? 18 : $resultCount;
					for ($i = 0; $i<$thumbnails; $i++) {
						$image = $result->data[$i];
						$output .= '<div class="slide"><img src="'.$image->images->standard_resolution->url.'" width="468" height="468"></div>';
					}
					print $prompt.'<script>$("#slick .slideshow").html(\''.$output.'\'); galleryInit(); $("#gallery:hidden").fadeIn(fadeDelay); $("#galleryOpen").addClass("active");</script>';
					$output = true;
				}
				else output('<p class="error">'.$joshua.'Found nothing tagged with '.$input.'. (Instagram filters the API rigorously.)');
			}
			else error('empty');
		}
		else output('<p class="error">'.$joshua.'Give me something to search for.<p class="example">'.$command.' daft punk');
	}

	// rand
	if ($command == "rand") {
		if (isset($option)) {
			if (is_numeric($option)) {
				output(rand(1, $option));
			}
			else output('<p class="error">'.$joshua.' '.$option.' is not a number. This error message will hopefully never be seen.');
		}
		else output('<p class="error">'.$joshua.'Between how many numbers?<p class="example">'.$command.' '.rand(0,10));
	}

	// git
	if ($command == "git") {
		if (isset($option)) {
			if ($option == "log") {
				$url = 'https://api.github.com/repos/destru/joshua/commits';
				$commits = json_decode(get($url));
				if (count($commits)) {
					print $prompt;
					foreach (array_reverse($commits) as $i) {
						print '<p>'.
							'<span class="dark">'.date("F j, Y", strtotime($i->commit->author->date)).'</span><br>'.
							$i->commit->message.'<br>'.
							'<a class="external fixed-width" href="'.$i->html_url.'">'.substr($i->sha, 0, 7).'</a>'.
							'';
					}
					$output = true;
				}
			}
			else error('auth');
		}
		else output('<p class="error">'.$joshua.'Please specify an operation to perform.<p class="example">'.$command.' log');
	}

	// flip
	if ($command == "flip") {
		if (isset($input)) {
			$flipped = array(
				'a' => '&#592;',
				'b' => 'q',
				'c' => '&#596;',
				'd' => 'p',
				'e' => '&#477;',
				'f' => '&#607;',
				'g' => '&#387;',
				'h' => '&#613;',
				'i' => '&#305;&#803;',
				'j' => '&#638;',
				'k' => '&#670;',
				'l' => '&#1503;',
				'm' => '&#623;',
				'n' => 'u',
				'o' => 'o',
				'p' => 'd',
				'q' => 'b',
				'r' => '&#633;',
				's' => 's',
				't' => '&#647;',
				'u' => 'n',
				'v' => '&#652;',
				'w' => '&#653;',
				'x' => 'x',
				'y' => '&#654;',
				'z' => 'z',
				'.' => '&#729;',
				'?' => '&#191;',
				'!' => '&#161;',
				'\'' => ',',
				',' => '\'',
				' ' => ' ',
				'0' => '0',
				'1' => '&#406;',
				'2' => '&#4357;',
				'3' => '&#400;',
				'4' => '&#12579;',
				'5' => '&#987;',
				'6' => '9',
				'7' => '&#12581;',
				'8' => '8',
				'9' => '6'
			);
			$chars = str_split(strrev(strtolower($input)));
			$output .= '(╯°□°）╯︵ ';
			foreach($chars as $char) {
				$output .= $flipped[$char];
			}
			output($output);
		}
		else output('<p class="error">'.$joshua.'What do you want flipped?<p class="example">'.$command.' seahawks rule');
	}

	// wiki
	if ($command == "wiki" || $command == "wikipedia") {
		if (isset($input)) {
			$wiki = 'http://en.wikipedia.org/w/api.php?action=query&list=search&srsearch='.urlencode($input).'&srprop=snippet&format=json';
			$wiki = json_decode(get($wiki));
			if (count($wiki->query->search) > 0) {
				print $prompt;
				foreach ($wiki->query->search as $article) {
					print '<p>'.
						substr(strip_tags($article->snippet), 0, 100).'&hellip;<br>'.
						'<a class="external" href="http://en.wikipedia.org/wiki/'.$article->title.'">'.$article->title.'</a>'.
						'';
				}
				$output = true;
			}
			else error('404');
		}
		else output('<p class="error">'.$joshua.'What am I looking for?<p class="example">'.$command.' wonder showzen');
	}

	// history
	if ($command == "history") {
		if (isset($option) && $option == "clear") {
			deleteCookie('history');
			output('<p class="joshua">'.$joshua.'History was cleared.');
		}
		else {
			$history = explode(',', $_COOKIE['history']);
			output(implodeHuman($history, true).'.');
		}
	}

	// window management
	$jsCommands = array('clear', 'cls', 'exit', 'quit', 'logout', 'customize', 'music', 'videos', 'superplastic', 'reset');
	if (in_array($command, $jsCommands)) {
		if ($command == "clear" || $command == "cls") {
			$js = "$('#output').html('');";
		}
		else if ($command == "exit" || $command == "quit" || $command == "logout") {
			$js = 'location.href = "http://binaerpilot.no";';
		}
		else if ($command == "superplastic") $js = 'loadSuperplastic();';
		else if ($command == "videos") $js = 'loadVideos();';
		else if ($command == "reset") $js = 'reset();';
		else if ($command == "customize" || $command == "music") {
			setcookie($command, true, $cookieExpires, '/');
			$js = '$("#'.$command.':hidden").fadeIn(fadeDelay); $("#'.$command.'Open").addClass("active");';
			if ($command == "music") {
				$js .= 'mute();';
			}
		}
		$js .= 'systemReady();';
		print '<script>'.$js.'</script>';
		$output = true;
	}

	// fallback
	if (empty($output)) {
		foreach ($static as $key => $value) {
			if ($key == $command) output($value);
		}
		if (empty($output)) {
			$storage = "invalid.data";
			if (!file_exists($storage)) touch($storage);
			$fp = fopen($storage, 'a');
			fwrite($fp, $dump."\n");
			fclose($fp);
			error('invalid');
		}
	}
}
?>
