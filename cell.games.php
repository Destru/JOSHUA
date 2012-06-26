<?php // static
$games = array(
	'ao' => array(
		'api' => 'http://people.anarchy-online.com/character/bio/d/1/name/binaerpilot/bio.xml',
		'format' => 'xml',
		'about' => '<p><b>Anarchy Online</b> blew my mind when I first played it 6 years ago and it\'s still my greatest game experience bar none. '.
			'In awe of the sheer size and complexity of the game I quickly found myself completely immersed in it. '.
			'I made <a href="misc/aoscripts.rar">some scripts</a> that make things easier.</p>'
	),
	'eve' => array(
		'api' => 'https://api.eveonline.com/eve/CharacterInfo.xml.aspx?characterID=1761654327',
		'format' => 'xml',
		'about' => '<p><b>EVE Online</b> is a well-crafted world for those with enough time to invest. '.
			'Being a sandbox-game, it will be intimidating for new players as there is no clear path cut out for you. '.
			'Supporting the harshest PVP-enviroment in any MMO today, this one is certainly not for the faint-hearted. '.
			'There\'s a <a href="https://secure.eve-online.com/ft/?aid=103557">14-day trial available</a>. But be careful, this game is digital crack and has no pause button.</p>'
	),
	'wow' => array(
		'api' => 'http://eu.battle.net/api/wow/character/outland/destru?fields=pvp,feed,talents,titles',
		'format' => 'json',
		'about' => '<p><b>World of Warcraft</b> has been a guilty pleasure of mine on and off for years. '.
			'So far I\'ve played a coulple characters to end-game and messed around with more PvP alts than I can remember. '.
			'Even a die hard science fiction fan like myself must admit that the game is simply breath-takingly well executed.</p>'		
	),
	'sto' => array(
		'api' => 'http://www.startrekonline.com/character_profiles/918798/xml',
		'format' => 'xml',
		'about' => '<p>I didn\'t play <b>Star Trek Online</b> long enough for an well-rounded opinion. '.
			'That being said I did have fun, 70 hours worth according to Steam. Ultimately though the game didn\'t grip me.</p>'
	),
	'swtor' => array(
		'about' => '<p><b>Star Wars: The Old Republic</b> was a fantastic RPG, but unfortunately a terrible MMORPG. '.
			'I had a great time leveling my Bounty Hunter, but when the time came to PvP I was so put off by how poorly it played that my subscription ran out without me noticing. '.
			'I will probably give it another try in the future, but apart from a fantastic single-player experience the game was a disappointment.</p>'
	)
);

// see, we had all these API's...
function api($game, $api){
	if($game == 'ao'){
		$output = '<table class="fluid">'.
			'<tr><td rowspan="7"><div class="image" style="background-image:url(\''.str_replace('www', 'people', $api->smallpictureurl).'\');width:60px;height:90px;"></div></td></tr>'.		
			'<tr><td class="dark">Name</td><td><a href="http://auno.org/ao/equip.php?saveid=177936">'.$api->name->firstname.' "'.$api->name->nick.'" '.$api->name->lastname.'</a></td></tr>'.
			'<tr><td class="dark">Profession</td><td>'.$api->basic_stats->profession.'</td></tr>'.
			'<tr><td class="dark">Level</td><td>'.$api->basic_stats->profession_title.' ('.$api->basic_stats->level.')</td></tr>'.
			'<tr><td class="dark">Defender</td><td>'.$api->basic_stats->defender_rank.' ('.$api->basic_stats->defender_rank_id.')</td></tr>'.
			'<tr><td class="dark">Faction</td><td>'.$api->basic_stats->faction.'</td></tr>'.
			'<tr><td class="dark">Organization</td><td>'.$api->organization_membership->organization_name.'</td></tr>'.
			'</table>';
	}
	else if($game == 'eve'){
		$output = '<table class="fluid">'.
			'<tr><td rowspan="7"><div class="image" style="background-image:url(\'http://image.eveonline.com/Character/'.$api->result->characterID.'_64.jpg\');width:64px;height:64px;"></div></td></tr>'.
			'<tr><td class="dark">Name</td><td>'.$api->result->characterName.'</td></tr>'.
			'<tr><td class="dark">Race</td><td>'.$api->result->race.' ('.$api->result->bloodline.')</td></tr>'.
			'<tr><td class="dark">Corporation</td><td>'.$api->result->corporation.'</td></tr>'.
			'<tr><td class="dark">Alliance</td><td><a href="http://rust-in-pieces.org/kills/">'.$api->result->alliance.'</a></td></tr>'.
			'<tr><td class="dark">Security Status</td><td>'.number_format(floatval($api->result->securityStatus), 2).'</td></tr>'.
			'</table>';
	}
	else if($game == 'wow'){
		// get specs
		foreach($api->talents as $talent) $talents[] = $talent->name.' ('.$talent->trees[0]->total.'/'.$talent->trees[1]->total.'/'.$talent->trees[2]->total.')';
		// set correct title
		foreach($api->titles as $title) if(isset($title->selected)) $currentTitle = $title->name;
		if(isset($currentTitle)) $name = str_replace('%s', $api->name, $currentTitle);
		else $name = $api->name;
		// grab recent events
		$feed = array_filter($api->feed, function($i){
			if(in_array($i->type, array('BOSSKILL', 'ACHIEVEMENT'))) return true;
		});	
		$feed = array_values($feed);
		for($i = 0; $i < 5; $i++){
			$title = $feed[$i]->achievement->title;
			$points = $feed[$i]->achievement->points;
			if(!empty($title)){
				if(!empty($points)) $events[] = $title.' <span class="light">+'.$points.'</span>';
				else $events[] = $title;				
			}
		}
		$output = '<table class="fluid">'.
			'<tr><td rowspan="7"><div class="image" style="background-image:url(\'http://eu.battle.net/static-render/eu/'.$api->thumbnail.'\');width:84px;height:84px;"></div></td></tr>'.
			'<tr><td class="dark">Name</td><td><a href="http://eu.battle.net/wow/en/character/'.$api->realm.'/'.$api->name.'/simple">'.$name.'</a></td></tr>'.
			'<tr><td class="dark">Realm</td><td>'.$api->realm.' ('.$api->battlegroup.')</td></tr>'.
			'<tr><td class="dark">Talents</td><td>'.implodeHuman($talents).'</td></tr>'.
			'<tr><td class="dark">Achievements</td><td>'.$api->achievementPoints.'</td></tr>'.
			'<tr><td class="dark">Honorable Kills</td><td>'.$api->pvp->totalHonorableKills.'</td></tr>'.
			'<tr><td class="dark">Recent Activity</td><td>';
		foreach($events as $event) $output .= $event.'<br/>';
		$output .= '</td></tr></table>';
	}
	else if($game == 'sto'){
		$output = '<table class="fluid">'.
			'<tr><td class="dark">Name</td><td><a href="http://www.startrekonline.com/character_profiles/'.$charid.'">'.$api->cdata->name.'@'.$api->cdata->display_name.'</a></td></tr>'.
			'<tr><td class="dark">Class</td><td>'.str_replace('_', ' ', $api->cdata->class).'</td></tr>'.
			'<tr><td class="dark">Rank</td><td>'.$api->cdata->rank.' ('.$api->cdata->level.')</td></tr>'.
			'<tr><td class="dark">Ship</td><td>'.$api->ship->name.'</td></tr>'.
			'<tr><td class="dark">Serial</td><td>'.$api->ship->serial.'</td></tr>'.
			'</table>';
	}
	return $output;
}

// games
if($command == 'games' || $command == 'game'){
	$gameList = array_keys($games);
	if(isset($option) && !empty($games[$option])){
		$game = $option;
		$cache = $game.'.'.$games[$game]['format'];
		print $prompt.$games[$game]['about'];
		if(!empty($games[$game]['api'])){
			get($games[$game]['api'], $cache, 1);
			$api = load($cache, 1);
			print api($game, $api);
		}
		$output = 1;
	}
	else output('<p class="error">'.$joshua.'Valid options are '.implodeHuman($gameList).'.</p><p class="example">'.$command.' '.$gameList[rand(0,count($gameList)-1)].'</p>');
}

// xbox
if($command == "xbox"){
	$url = 'https://xboxapi.com/profile/Destru+Kaneda';
	$cache = 'xbox.json';
	get($url, $cache);
	$json = load($cache);
	$gameList = array();
	foreach($json->RecentGames as $game) $gameList[] = $game->Name;
	output('<table class="fluid">'.
		'<tr><td rowspan="6"><div class="image" style="background-image:url(\''.$json->Player->Avatar->Gamerpic->Large.'\');width:64px;height:64px;"></div></td></tr>'.
		'<tr><td class="dark">Gamertag</td><td>'.$json->Player->Gamertag.'</td></tr>'.
		'<tr><td class="dark">Activity</td><td>'.$json->Player->Status->Online_Status.'</td></tr>'.
		'<tr><td class="dark">Recently</td><td>'.implodeHuman($gameList).'</td></tr>'.
		'<tr><td class="dark">Gamerscore</td><td>'.$json->Player->Gamerscore.'</td></tr></table>');
}

?>