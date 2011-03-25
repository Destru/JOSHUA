<?php // joshua engine <alexander@binaerpilot.no>
session_start(); // initialize a session
if($_SERVER['HTTP_HOST'] == "localhost" || $_SERVER['HTTP_HOST'] == "127.0.0.1" ) $dev = 1; // development mode set
if(!empty($_POST['command'])) $command = strip_tags(trim($_POST['command']));
if(!empty($_POST['option'])) $option = strip_tags(trim($_POST['option']));
if(!empty($_POST['dump'])) $dump = strip_tags(trim($_POST['dump']));
if(!empty($option) && $option == "undefined") unset($option);
if(!empty($dump) && $dump == "undefined") unset($dump);
$joshua = '<strong>Joshua:</strong> ';
$output = '';

// functions
function error($input){
	global $error,$output,$command,$prompt,$joshua;
	print $prompt.'<p class="error">'.$error[$input].'</p>';
	die();
}
function output($response){
	global $output,$command,$option,$prompt,$joshua;
	if(stristr($response,'<p') || stristr($response,'<table')) print $prompt.$response;
	else print $prompt.'<p>'.$response.'</p>';
	$output = 1;
}
function get($url, $cache = 0){
	global $dev;
	$timeout = 10;
	$secondsBeforeUpdate = 60;
	if(!isset($dev)){
		if(!empty($cache)){
			if(!file_exists($cache)) touch($cache);
			$lastModified = filemtime($cache);
			if(time() - $lastModified > $secondsBeforeUpdate){
				$ch = curl_init();
				curl_setopt ($ch, CURLOPT_URL, $url);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
				$urlData = curl_exec($ch);
				if(!empty($urlData)){
					$handle = fopen($cache, "w");
					fwrite($handle, $urlData);
					fclose($handle);
				}
				else error('timeout');
				curl_close($ch);
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
function microtimer($timestamp){
	return round(microtime(true)-$timestamp, 5);
}
function dbFile($filename){
	$file = file($filename);
	$data = array();
	foreach ($file as $lineNum => $line){
	if(!empty($line))
		$data[$lineNum] = explode('^', trim($line));
		else return false;
	}
	return $data;
}

// errors	
$error = array(
	'404' => $joshua.'No such key found',
	'invalid' => $joshua.'The command <em>'.$command.'</em> is invalid.',
	'blocked' => $joshua.'Input did not pass security.',
	'notip' => $joshua.'Not a valid IP address.',
	'notdomain' => $joshua.'Illegal domain name.',
	'noreturn' => $joshua.'Host system did not respond to <em>'.$command.'</em>.',
	'strlong' => $joshua.'Input is over the <em>'.$command.'</em> limit.',
	'strshort' => $joshua.'Input has failed to meet <em>'.$command.'</em> minimum length.',
	'auth' => $joshua.'You are not authorized to issue that command.',
	'timeout' => $joshua.'Request timed out. Please try again later.'
);

// security
if(!empty($command)){
	$pattern = "/^[[:alnum:][:space:]:.\,\'-?!\*+%]{0,160}$/";
	if(!empty($dump) && preg_match($pattern, $dump) || empty($dump)){
		if(!empty($option)){
			$prompt = '<div class="prompt">'.$command.' <strong>'.$option.'</strong></div>';
		}
		else {
			$prompt = '<div class="prompt">'.$command.'</div>';
		}
	}
	else {
		$prompt = '<div class="prompt">error</div>';
		error('blocked');
	}
}

if(empty($output)){
	include('brain.php');
	// motd 
	if($command == "motd"){
		$count = count($quotes)-1; $rand = rand(0,$count);
		print '<p class="dark motd">'.$quotes[$rand].'</p><p class="joshua">'.$joshua.'Please enter <em>help</em> for commands.</p>'; $output = 1;
	}
	// quotes, pearls, bash
	if($command == "quote" || $command == "bash" || $command == "pearl"){
		if($command == "bash") $array = $bash;
		elseif($command == "quote") $array = $quotes;
		elseif($command == "pearl") $array = $pearls;
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
			output($quote);		}
	}
	// uptime and date
	if($command == "uptime" || $command == "date"){
		$return = trim(exec($command));
		if(!empty($return))	output($return);
		else error('noreturn');
	}
	// whois
	if($command == "whois"){
		if(empty($option)) output('<p>You need to specify a domain name.</p><p class="example">whois binaerpilot.no</p>');
		else {
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
	}
	// prime number
	if($command == "prime"){
		if(empty($option)) output('<p>You need to specify a number.</p><p class="example">prime 13</p>');
		else {
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
				output('<pre>'.$output.'</pre><a class="external" href="http://maps.google.com/maps?q='.$latitude.'+'.$longitude.'">View at Google Maps.</a>');
			}
			else output('<pre>'.$output.'</pre>');
		}
		else error('notip');
	}
	// sudo
	if($command == "sudo"){
		if(empty($option)){
			if(empty($_SESSION['sudo'])) error('auth');
			else output('<p class="joshua">'.$joshua.'You\'re already authenticated.');
		}
		else if(!empty($option)){
			if($option == "joshua"){
				$_SESSION['sudo'] = "true";
				output('<p class="joshua">'.$joshua.'Authentification successful.</p><p>Welcome back, Alexander. What do you want to do today?</p>');
			}
			else {
				unset($_SESSION['sudo']);
				output('<p class="error">'.$joshua.'Incorrect password.</p>');
			}
		}
	}
	// todo
	if($command == "ls" || $command == "cd" || $command == "top" || $command == "rm" || $command == "top" || $command == "who"){
		if(!empty($_SESSION['sudo'])){
			output('Make these work.');
		}
		else error('auth');
	}
	// numbers
	if($command == "numbers" || $command == "n"){
		if(empty($_SESSION['numbers'])) $_SESSION['numbers'] = 0;
		if(empty($option)){
			$level = $_SESSION['numbers']+1;
			$levels = count($numbers);
			if($level != 1) output('<p><span class="light">Level '.$level.':</span> '.$numbers[$_SESSION['numbers']][0].'</p>');
			else output('<p>There are '.$levels.' levels. Answer by typing <span class="dark">n (x)</span>. Good luck!</p><p><span class="light">Level '.$level.':</span> '.$numbers[$_SESSION['numbers']][0].'</p>');
		}
		else if(!empty($option)){
			if($option == $numbers[$_SESSION['numbers']][1]){
				$_SESSION['numbers'] = $_SESSION['numbers']+1;
				$level = $_SESSION['numbers']+1;
				output('<p><span class="light">Level '.$level.':</span> '.$numbers[$_SESSION['numbers']][0].'</p>');
			}
			else if($option == "reset"){
				unset($_SESSION['numbers']);
				output($joshua.'Game reset.');
			}
			else output('<p class="dark">Wrong answer. Try again.</p><p>'.$numbers[$_SESSION['numbers']][0].'</p>');
		}
	}
	// msg
	if($command == "msg"){
		$storage = "msg.data";
		$length = trim(strlen(str_replace($command, '', $dump)));
		if($length > 0){
			if($length > 140) error('strlong');
			else {
				$message = explode("msg ",$dump);
				$message = $message[1];
				if($message == "all") $all = 1;
				if($message != "list" && $message != "all"){
					if($length < 8) error('strshort');
					$timestamp = date("d/m/y");
					$fp = fopen($storage, 'a');
					fwrite($fp, $timestamp.'^'.$message.'^'.$_SERVER['REMOTE_ADDR']."\n");
					fclose($fp);
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
				$limit = 10; if(isset($all)) $limit = count($messages);
				$output = '<pre class="messages">';
				for ($i = 0; $i < $limit; $i++){
					$id = $i+1;
					$output .= '<span class="light">'.$messages[$i]['timestamp'].'</span> '.$messages[$i]['message']."\n";
				}
				$output .= '</pre>';
				output($output);
			}
		}
		else output('<p>Please leave a message after the beep. <140 characters (alphanumeric). <em>Beep!</em></p><p class="example">msg joshua needs more ultraviolence</p>');
	}
	// yoda
	if($command == "yoda"){
		$yodaPixel = '<div class="pixelPerson"><img src="images/iconYoda.png" width="27" height="28" /></div>';
		$length = strlen($dump);
		if($length > 6	){
			$question = str_replace('yoda ','',$dump);
			if(!stristr($question, '?')) $question .= '?';
			$count = count($yoda)-1; $rand = rand(0,$count);
			print '<div class="prompt">'.$command.' <strong>'.$question.'</strong></div><div class="speechBubble">'.$yoda[$rand].'</div>'.$yodaPixel; $output = 1;
		}
		else output('<p class="chat">Ask a question you must.</p>'.$yodaPixel);
	}
	// fml
	if($command == "fml"){
		$url = "http://feeds.feedburner.com/fmylife?format=xml";
		$cache = "fml.xml";
		get($url, $cache);
		$xml = simplexml_load_file($cache);
		output($xml->entry[rand(0,9)]->content);
	}
	// game ao
	if($command == "game" && !empty($option) && $option == "ao"){
		$char = 'quickhack';
		$url = 'http://people.anarchy-online.com/character/bio/d/1/name/'.$char.'/bio.xml';
		$cache = 'ao.xml';
		get($url, $cache);
		$xml = simplexml_load_file($cache);

		$name = $xml->name->firstname.' '.$xml->name->lastname;
		output('<p><em>Anarchy Online</em> blew my mind when I first played it 6 years ago and it\'s still my greatest game experience bar none.
			The sheer size and complexity of the game was unparallalled at the time and I quickly found myself completely immersed in it.
			<a href="misc/aoscripts.rar">Download some scripts</a> or listen to <a href="misc/doktor_dreiebenk_-_the_doctor_is_in.mp3">this little rap song</a>.
			Both more than indicative of my former Rubi-ka addiction.
			I\'ve also coded a little for various botnets and made more silly little sites than I can remember.
			</p>'.
			'<table class="fluid"><tr><td rowspan="7"><div class="image" style="background-image:url(\'images/aoQuickhack.png\');width:100px;height:100px;"></div></td></tr>'.
			'<tr><td class="dark">Name</td><td><a href="'.$url.'">'.$xml->name->nick.'</a></td></tr>'.
			'<tr><td class="dark">Profession</td><td>'.$xml->basic_stats->faction.' '.$xml->basic_stats->profession.'</td></tr>'.
			'<tr><td class="dark">Title</td><td>'.$xml->basic_stats->profession_title.' ('.$xml->basic_stats->level.')</td></tr>'.
			'<tr><td class="dark">Organization</td><td>'.$xml->organization_membership->organization_name.'</td></tr>'.
			'<tr><td class="dark">Rank</td><td>'.$xml->organization_membership->rank.'</td></tr>'.
			'<tr><td class="dark">Status</td><td class="light">Inactive</td></tr>'.
			'</table>');
	}
	// game eve
	if($command == "game" && !empty($option) && $option == "eve"){
		$charid = '1761654327';
		$url = 'http://api.eve-online.com/char/CharacterSheet.xml.aspx?userID=3292896&apiKey=2F975C46AD0E4944B92A1593424E96473C8D729993DF46D0BABB4EA1C2C4E88B&characterID='.$charid;
		$cache = 'eve.xml';
		get($url, $cache);
		$xml = simplexml_load_file($cache);
		
		// print it out
		$name = $xml->result->name;
		$race = $xml->result->race;
		$bloodline = $xml->result->bloodLine;
		$gender = $xml->result->gender;
		$corp = $xml->result->corporationName;
		$clone = $xml->result->cloneSkillPoints;
		$balance = $xml->result->balance;
		output('<p><em>EVE Online</em> is a well-crafted world for those with enough time to invest. '.
			'Being a sandbox-game, it will be intimidating for new players as there is no clear path cut out for you. '.
			'Supporting the harshest PVP-enviroment in any MMO today, this one is certainly not for the faint-hearted. '.
			'I have made some <a href="http://binaerpilot.no/alexander/eve/">cheat sheets</a> and there\'s a <a href="https://secure.eve-online.com/ft/?aid=103557">14-day trial available</a>.</p>'.
			'<table class="fluid"><tr><td rowspan="7"><div class="image" style="background-image:url(\'images/eveDestruKaneda.png\');width:100px;height:100px;"></div></td></tr>'.
			'<tr><td class="dark">Name</td><td>'.$name.'</td></tr>'.
			'<tr><td class="dark">Race</td><td>'.$race.' ('.$bloodline.')</td></tr>'.
			'<tr><td class="dark">Corporation</td><td><a href="http://www.minmatar-militia.org/kb/?a=corp_detail&crp_id=3361">'.$corp.'</a></td></tr>'.
			'<tr><td class="dark">Piloting</td><td>Nostromo</td></tr>'.
			'<tr><td class="dark">Wealth</td><td>'.$balance.' ISK</td></tr>'.
			'<tr><td class="dark">Status</td><td class="light">Active</td></tr>'.
			'</table>');
	}
	// game wow 
	if($command == "game" && !empty($option) && $option == "wow"){
		$realm = "Skullcrusher"; $character = "Fenrisúlfr";
		$url = 'http://eu.wowarmory.com/character-sheet.xml?r='.$realm.'&n='.$character.'&rhtml=n';
		$url_custom = 'http://eu.wowarmory.com/character-feed.atom?r='.$realm.'&cn='.$character.'&locale=en_US';
		$wowhead = 'http://www.wowhead.com/user=Destru#characters';
		$cache = 'wow.xml'; $cache_custom = 'wow.custom.xml';
		get($url, $cache);
		get($url_custom,  $cache_custom);
		$xml = simplexml_load_file($cache);
		$xml_custom = simplexml_load_file($cache_custom);
		
		if(filesize($cache) > 2000){
			$name = $xml->characterInfo->character->attributes()->name;
			$class = $xml->characterInfo->character->attributes()->class;
			$level = $xml->characterInfo->character->attributes()->level;
			$points = $xml->characterInfo->character->attributes()->points;
			$faction= $xml->characterInfo->character->attributes()->faction;
			$updated = $xml->characterInfo->character->attributes()->lastModified;
			$spec = $xml->characterInfo->characterTab->talentSpecs->talentSpec[0]->attributes()->prim;
			$specDetails =  $xml->characterInfo->characterTab->talentSpecs->talentSpec[0]->attributes()->treeOne.'/'.
				$xml->characterInfo->characterTab->talentSpecs->talentSpec[0]->attributes()->treeTwo.'/'.
				$xml->characterInfo->characterTab->talentSpecs->talentSpec[0]->attributes()->treeThree;
			$altSpec = $xml->characterInfo->characterTab->talentSpecs->talentSpec[1]->attributes()->prim;	
			$altSpecDetails  = $xml->characterInfo->characterTab->talentSpecs->talentSpec[1]->attributes()->treeOne.'/'.
				$xml->characterInfo->characterTab->talentSpecs->talentSpec[1]->attributes()->treeTwo.'/'.
				$xml->characterInfo->characterTab->talentSpecs->talentSpec[1]->attributes()->treeThree;
			$kills = $xml->characterInfo->characterTab->pvp->lifetimehonorablekills->attributes()->value;
			$events = '';
			for ($i = 0; $i < 5; $i++){
				$event = $xml_custom->entry[$i]->content;
				if(!empty($event)) $events .= $event.'<br/>';
			}
			$details = '<table class="fluid"><tr><td rowspan="8"><div class="image" style="background-image:url(\'images/fenris.png\');width:100px;height:100px;"></div></td></tr>'.
			'<tr><td class="dark">Name</td><td><a href="'.$wowhead.'">'.$name.'</a></td></tr>'.
			'<tr><td class="dark">Faction</td><td>'.$faction.' '.$class.'</td></tr>'.
			'<tr><td class="dark">PvE</td><td>'.$altSpec.' ('.$altSpecDetails.')</td></tr>'.
			'<tr><td class="dark">PvP</td><td>'.$spec.' ('.$specDetails.')</td></tr>'.
			'<tr><td class="dark">Achievements</td><td>'.$points.'</a></td></tr>'.
			'<tr><td class="dark">Status</td><td class="light">Active</td></tr>'.
			'</table>';
			if(filesize($cache_custom) > 2000) $details .= '<div class="pre dark">'.$events.'</div>';
		}
		else {
			$details = '<p><a class="external" href="'.$wowhead.'">View my Wowhead profile.</a></p>';
		}

		output('<p><em>World of Warcraft</em> has been a guilty pleasure of mine on and off for years. '.
			'So far I\'ve played three characters to end-game and messed around with more PvP alts than I can remember. '.
			'Even a die hard science fiction fan like myself must admit that the game is simply breath-takingly well executed. '.
			'For the Horde!</p>'.$details);
	}
	// game sto
	if($command == "game" && !empty($option) && $option == "sto"){
		$charid = '918798';
		$url = 'http://www.startrekonline.com/character_profiles/'.$charid.'/xml';
		$cache = 'sto.xml';
		get($url, $cache);
		$xml = simplexml_load_file($cache);
		
		$character = '<table class="fluid"><tr><td rowspan="7"><div class="image" style="background-image:url(\'images/sto.png\');width:100px;height:100px"></div></td></tr>'.
			'<tr><td class="dark">Name</td><td><a href="http://www.startrekonline.com/character_profiles/'.$charid.'">'.$xml->cdata->name.'@'.$xml->cdata->display_name.'</a></td></tr>'.
			'<tr><td class="dark">Class</td><td>'.str_replace('_', ' ', $xml->cdata->class).'</td></tr>'.
			'<tr><td class="dark">Rank</td><td>'.$xml->cdata->rank.' ('.$xml->cdata->level.')</td></tr>'.
			'<tr><td class="dark">Ship</td><td>'.$xml->ship->name.'</td></tr>'.
			'<tr><td class="dark">Serial</td><td>'.$xml->ship->serial.'</td></tr>'.
			'<tr><td class="dark">Status</td><td class="light">'.$xml->cdata->location.'</td></tr></table>';
		output('<p>I didn\'t play <em>Star Trek Online</em> long enough for an educated opinion. That being said I did have fun, 70 hours worth according to Steam. Ultimately the game was a bit shallow for my taste.</p>'.$character);
	}
	// xbox
	if($command == "xbox"){
		$charid = "Destru%20Kaneda";
		$url = 'http://xboxapi.duncanmackenzie.net/gamertag.ashx?GamerTag='.$charid;
		$cache = 'xbox.xml';
		get($url, $cache);
		$xml = simplexml_load_file($cache);

		// print it out
		if($xml->AccountStatus != "Unknown"){
			$name = $xml->Gamertag;
			$reputation = $xml->Reputation;
			$score = $xml->GamerScore;
			$status = $xml->PresenceInfo->StatusText;
			$info = $xml->PresenceInfo->Info;
			$moreInfo = $xml->PresenceInfo->Info2;
			if(strlen($moreInfo) > 1) $info = $info.' ('.$moreInfo.')';
			$games = '';
			for ($i = 0; $i < 3; $i++){
				$game = $xml->RecentGames->XboxUserGameInfo[$i]->Game->Name;
				$gameScore = $xml->RecentGames->XboxUserGameInfo[$i]->GamerScore;
				$gameDetails = $xml->RecentGames->XboxUserGameInfo[$i]->DetailsURL;
				$games .= $game.' ('.$gameScore.')';
				if($i != 2) $games .= ', ';
			}
			output('<table class="fluid"><tr><td rowspan="6"><div class="image" style="background-image:url(\'http://avatar.xboxlive.com/avatar/'.$charid.'/avatarpic-l.png\');width:64px;height:64px;"></div></td></tr>'.
				'<tr><td class="dark">Gamertag</td><td><a href="http://live.xbox.com/member/'.$charid.'">'.$name.'</a> ('.$status.')</td></tr>'.
				'<tr><td class="dark">Activity</td><td>'.$info.'</td></tr>'.
				'<tr><td class="dark">Recent</td><td>'.$games.'</td></tr>'.
				'<tr><td class="dark">Reputation</td><td>'.$reputation.'%</td></tr>'.
				'<tr><td class="dark">Gamerscore</td><td>'.$score.'</td></tr></table>');
		}
		else {
			output('Failed to retrieve data from Xbox Live.');
		}
	}
	// games
	if($command == "game" || $command == "games"){
		$gameList = '';
		if(!empty($option)){
			foreach ($games as $key => $value){
				if($key == $option) output($value);
			}
			// 404
			if(empty($output)) error('404');
		}
		else {
			foreach ($games as $key => $value) $gameList[] .= '<em>'.$key.'</em>';
			$gameList = implode(', ', $gameList);
			output('<p>Valid choices are '.$gameList.', <em>wow</em>, <em>sto</em>, <em>eve</em> and <em>ao</em>.</p><p class="example">game eve</p>');
		}
	}
	// cheat
	if($command == "idkfa"){
		$commands = array();
		foreach ($static as $key => $value){
			$commands[] .= '<em>'.$key.'</em>';
		}
		sort($commands); $commands = implode(', ', $commands);
		print output('<p class="joshua">'.$joshua.'Listing all the keys...</p><p>'.$commands.'.</p>');
	}
	// lastfm
	if($command == "last.fm" || $command == "lastfm"){
		// loved tracks
		$url = 'http://ws.audioscrobbler.com/2.0/?method=user.getlovedtracks&user=astoever&api_key=a2b73335d53c05871eb50607e5df5466';
		$count = 8; $cache = 'lastfm.loved.xml';
		get($url, $cache);

		// print it out
		$xml = simplexml_load_file($cache);
		print '<div class="prompt">last.fm <strong>Loved</strong></div><p>';
		for ($i = 0; $i < $count; $i++){
			$track = $xml->lovedtracks->track[$i]->name;
			$artist = $xml->lovedtracks->track[$i]->artist->name;
			print $artist.' - '.$track.'<br/>'."\r";
		}
		// recent tracks
		$url = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=astoever&api_key=a2b73335d53c05871eb50607e5df5466';
		$count = 8; $cache = 'lastfm.xml';
		get($url, $cache);

		// print it out
		$xml = simplexml_load_file($cache);
		print '<div class="prompt">last.fm <strong>Recent</strong></div><p>';
		for ($i = 0; $i < $count; $i++){
			$track = $xml->recenttracks->track[$i]->name;
			$artist =$xml->recenttracks->track[$i]->artist;
			print $artist.' - '.$track.'<br/>'."\r";
		}
		print '<a class="external" href="http://last.fm/user/astoever/" title="Alexander Støver on Last.FM">More useless data.</a></p>'; $output = 1;
	}
	// wtfig
	if($command == "wtfig"){
		if(!isset($option)){
			output('<p>You need to specify font and caption.</p><p class="example">wtfig chunky Awesome!</p>');
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
						$fontList[] = '<em>'.$fontName.'</em>';
					}
				}
				sort($fontList); $fonts = implode(', ', $fontList);
				$output = '<p>'.$fonts.'.</p>';
				if($option != "list"){
					$output = '<p class="error">'.$joshua.'Invalid font. See list below.</p>'.$output;
				}
				output($output);
			}
		}
	}
	// get (torrents)
	if($command == "get"){
		if(isset($option)){
			$rows = 20; $query = str_replace($command.' ', '', $dump);
			$url = 'http://ca.isohunt.com/js/json.php?ihq='.urlencode($query).'&start=0&rows='.$rows.'&sort=seeds';
			$content = get($url);
			if($content){
				print '<div class="prompt">'.$command.' <strong>'.$query.'</strong></div>';
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
						if($link){
							print '<tr><td class="torrent"><a href="'.$link.'" title="'.$title.'">'.$name.'</a></td><td class="dark">'.$seeds.'/'.$leechers.'</td></tr>';
						}
					}
					print '</table>';
				}
				else print '<p>There were no results for <em>'.$query.'</em>.</p>';
				$output = 1;
			}
			else {
				error('timeout');
			}
		}
		else output('<p>You need to specify something to look for.</p><p class="example">get binaerpilot</p>');
	} 
	// superplastic
	if($command == "superplastic"){
		if(!empty($_POST['name'])) $name = strip_tags(trim($_POST['name']));
		if(!empty($_POST['score'])) $score = strip_tags(trim($_POST['score']));
		$storage = "superplastic.data";
		if(!empty($name) && !empty($score)){
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
		print '<h2>Season IV</h2><ol>';
		$count = 10;
		for ($i = 0; $i < $count; $i++){
			print '<li><strong>'.$scores[$i]['name'].'</strong> <span class="score">'.$scores[$i]['score'].'</span></li>';
		}
		print '</ol>'; $output = 1;
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
			else output('<p>Does not compute.</p>');
		}
		else output('<p>There\'s nothing to calculate.</p><p class="example">calc 6*9</p>');
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
		$messages = count(explode("\n", file_get_contents('msg.data')));
		$scores = count(explode("\n", file_get_contents('superplastic.data')))+2147; // from version 1, 1.1, 1.2, 1.3
		$commands = count($static)+20;
		$quotes = count($quotes)+count($bash)+count($pearls);
		$stats = 
			'<table class="stats">'.
			'<tr><td class="light">Commands</td><td>'.$commands.'</td><td class="dark">Yes, there are at least that many</td></tr>'.
			'<tr><td class="light">Brain cells</td><td>'.$brainCells.'</td><td class="dark">All external files loaded by the brain</td></tr>'.
			'<tr><td class="light">Themes</td><td>'.$themes.'</td><td class="dark">Some themes have to be unlocked...</td></tr>'.
			'<tr><td class="light">Bytes</td><td>'.$bytes.'</td><td class="dark">Everything hand-coded with Notepad++</td></tr>'.
			'<tr><td class="light">Lines</td><td>'.$lines.'</td><td class="dark">Lines of code (no externals)</td></tr>'.
			'<tr><td class="light">Messages</td><td>'.$messages.'</td><td class="dark">Left with the msg command</td></tr>'.
			'<tr><td class="light">Scores</td><td>'.$scores.'</td><td class="dark">Superplastic record attempts</td></tr>'.
			'<tr><td class="light">Quotes</td><td>'.$quotes.'</td><td class="dark">Includes MOTD\'s and bash.org quotes</td></tr>'.
			'<tr><td class="light">Timer</td><td>'.microtimer($timestamp).'</td><td class="dark">The seconds it took to compile these stats</td></tr>'.
			'</table>';
		output($stats);
	}
	// check static commands
	if(empty($output)){
		foreach ($static as $key => $value){
			if($key == $command) output($value);
		}
		// static has nothing either, print invalid command
		if(empty($output)) error('invalid');
	}
}
?>