<?php // static
$games = array(
	'ao' => array(
		'api' => 'http://people.anarchy-online.com/character/bio/d/5/name/binaerpilot/bio.xml',
		'format' => 'xml',
		'about' => '<p><b>Anarchy Online</b> blew my mind when I first played it '.(date("Y")-2003).' years ago and it\'s still my greatest game experience bar none. '.
			'In awe of the sheer size and complexity of the game I quickly found myself completely immersed in it. '.
			'I made <a href="ao/aoscripts.rar">some scripts</a> that make things easier.</p>'
	),
	'eve' => array(
		'api' => 'https://api.eveonline.com/eve/CharacterInfo.xml.aspx?characterID=1761654327',
		'format' => 'xml',
		'about' => '<p><b>EVE Online</b> is a what every MMO should aspire to be; Another world. '.
			'It will be intimidating for new players as there is no clear path cut out for you, but for those that persist it is very rewarding. '.
			'Supporting the harshest PVP-environment of any MMO available today, this one is certainly not for the faint-hearted.</p>'.
			'<p><a href="https://secure.eveonline.com/trial/?invc=f861919b-eb94-437b-80d6-df84d952885f&action=buddy">Grab a free 21-day trial here.</a></p>'
	),
	'wow' => array(
		'api' => 'http://eu.battle.net/api/wow/character/outland/destru?fields=pvp,feed,talents,titles',
		'format' => 'json',
		'about' => '<p><b>World of Warcraft</b> has been a guilty pleasure of mine on and off for years. '.
			'So far I\'ve played a couple characters to end-game and messed around with more PvP alts than I can remember. '.
			'Even a die hard science fiction fan like myself must admit that the game is simply breathtakingly well executed.</p>'		
	),
	'tsw' => array(
		'api' => 'http://chronicless.einhyrning.com/character/destru.json',
		'format' => 'json',
		'about' => '<p><b>The Secret World</b> is a breath of fresh air. Investigation missions are fantastic, as is character progression. '.
			'The game is scary, difficult and outright intimidating. Funcom definitely has a sleeper hit on their hands. '.
			'I like it so much that I even made a <a href="http://chronicless.einhyrning.com/">JSON API for it</a> (as you can see below).</p>'
	),
	'lol' => array(
		'about' => '<p><b>League of Legends</b> is an awesome, twitchy game with a surprising amount of depth. '.
			'My summoner name is "Destru Kaneda" on EU West (and Nordic, but I rarely play there).</p>'
	)
);

// see, we had all these API's...
function api($game, $api) {
	if($game == 'ao') {
		if ($api->name) {
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
		else error('invalidxml', 1);
	}
	else if($game == 'eve') {
		if ($api->result->characterName) {
			$output = '<table class="fluid">'.
				'<tr><td rowspan="7"><div class="image" style="background-image:url(\'http://image.eveonline.com/Character/'.$api->result->characterID.'_64.jpg\');width:64px;height:64px;"></div></td></tr>'.
				'<tr><td class="dark">Name</td><td><a href="http://eve.battleclinic.com/killboard/combat_record.php?type=player&name=Destru+Kaneda">'.$api->result->characterName.'</a></td></tr>'.
				'<tr><td class="dark">Race</td><td>'.$api->result->race.' ('.$api->result->bloodline.')</td></tr>'.
				'<tr><td class="dark">Corporation</td><td>'.$api->result->corporation.'</td></tr>'.
				'<tr><td class="dark">Alliance</td><td><a href="http://rust-in-pieces.org/kills/">'.$api->result->alliance.'</a></td></tr>'.
				'<tr><td class="dark">Security Status</td><td>'.number_format(floatval($api->result->securityStatus), 2).'</td></tr>'.
				'</table>';
			}
			else error('invalidxml', 1);
	}
	else if($game == 'wow') {
		// set correct title
		foreach($api->titles as $title) if(isset($title->selected)) $currentTitle = $title->name;
		if(isset($currentTitle)) $name = str_replace('%s', $api->name, $currentTitle);
		else $name = $api->name;
		// grab recent events
		$feed = array_filter($api->feed, function($i) {
			if(in_array($i->type, array('BOSSKILL', 'ACHIEVEMENT'))) return true;
		});	
		$feed = array_values($feed);
		for($i = 0; $i < 5; $i++) {
			$title = $feed[$i]->achievement->title;
			$points = $feed[$i]->achievement->points;
			if(!empty($title)) {
				if(!empty($points)) $events[] = $title.' <span class="light">+'.$points.'</span>';
				else $events[] = $title;				
			}
		}
		$output = '<table class="fluid">'.
			'<tr><td rowspan="6"><div class="image" style="background-image:url(\'http://eu.battle.net/static-render/eu/'.$api->thumbnail.'\');width:84px;height:84px;"></div></td></tr>'.
			'<tr><td class="dark">Name</td><td><a href="http://eu.battle.net/wow/en/character/'.$api->realm.'/'.$api->name.'/simple">'.$name.'</a></td></tr>'.
			'<tr><td class="dark">Realm</td><td>'.$api->realm.' ('.$api->battlegroup.')</td></tr>'.
			'<tr><td class="dark">Achievements</td><td>'.$api->achievementPoints.'</td></tr>'.
			'<tr><td class="dark">Honorable Kills</td><td>'.$api->pvp->totalHonorableKills.'</td></tr>'.
			'<tr><td class="dark">Recent Activity</td><td>';
		foreach($events as $event) $output .= $event.'<br/>';
		$output .= '</td></tr></table>';
	}
	else if($game == 'tsw') {
		$actives = ''; $passives = '';
		$iconSize = '24';
		foreach($api->actives as $slot) {
			$actives .= '<div class="image icon" title="'.$slot->name.'" style="display:inline-block;width:'.$iconSize.'px;height:'.$iconSize.'px;margin-right:5px;background-color:rgba(255,255,255,0.35);padding:2px;">'.
				'<img src="'.$slot->image->icon.'" class="icon" width="'.$iconSize.'" height="'.$iconSize.'">'.
				'</div>';
		}
		foreach($api->passives as $slot) {
			$passives .= '<div class="image icon" title="'.$slot->name.'" style="display:inline-block;width:'.$iconSize.'px;height:'.$iconSize.'px;margin-right:5px;background-color:rgba(255,255,255,0.15);padding:2px;">'.
				'<img src="'.$slot->image->icon.'" class="icon" width="'.$iconSize.'" height="'.$iconSize.'">'.
				'</div>';
		}
		$output = '<table class="fluid">'.
			'<tr><td class="dark">Name</td><td>'.$api->name.'</td></tr>'.
			'<tr><td class="dark">Faction</td><td>'.$api->faction->name.'</td></tr>'.
			'<tr><td class="dark">Cabal</td><td>'.$api->cabal.'</td></tr>'.
			'<tr><td class="dark">Build</td><td style="clear:both;">'.$actives.'<br>'.$passives.'</td></tr>';
		$output .= '</table>';
	}
	return $output;
}

// games
$gameList = array_keys($games);
sort($gameList);
if($command == 'games' || $command == 'game' || in_array($command, $gameList)) {
	if(in_array($command, $gameList)) {
		$option = $command;
		$command = 'game';	
	}
	if(isset($option) && !empty($games[$option])) {
		$game = $option;
		print $prompt.$games[$game]['about'];
		if(!empty($games[$game]['api']) && !empty($games[$game]['format'])) {
			$cache = $game.'.'.$games[$game]['format'];
			get($games[$game]['api'], $cache, 1);
			$api = load($cache, 1);
			print api($game, $api);
		}
		$output = 1;
	}
	else output('<p class="error">'.$joshua.'Valid options are '.implodeHuman($gameList, true).'.</p><p class="example">'.$command.' '.$gameList[rand(0,count($gameList)-1)].'</p>');
}

// xbox
if($command == "xbox") {
	$url = 'https://xboxapi.com/profile/Destru+Kaneda';
	$cache = 'xbox.json';
	get($url, $cache);
	$json = load($cache);
	$gameList = array();
	if($json->Success) {
		foreach($json->RecentGames as $game) $gameList[] = $game->Name;
		output('<table class="fluid">'.
			'<tr><td rowspan="6"><div class="image" style="background-image:url(\''.$json->Player->Avatar->Gamerpic->Large.'\');width:64px;height:64px;"></div></td></tr>'.
			'<tr><td class="dark">Gamertag</td><td>'.$json->Player->Gamertag.'</td></tr>'.
			'<tr><td class="dark">Activity</td><td>'.$json->Player->Status->Online_Status.'</td></tr>'.
			'<tr><td class="dark">Gamerscore</td><td>'.$json->Player->Gamerscore.'</td></tr>'.
			'<tr><td class="dark">Recently</td><td>'.implodeHuman($gameList).'</td></tr></table>');
	}
	else {
		error('empty');
	}
}

?>