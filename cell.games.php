<?php $games = array(
	'ao' => array(
		'api' => 'http://people.anarchy-online.com/character/bio/d/5/name/binaerpilot/bio.xml',
		'format' => 'xml',
		'about' => '<p><b>Anarchy Online</b> blew my mind when I first played it '.(date("Y")-2003).' years ago and it\'s still my greatest game experience bar none. '.
			'In awe of the sheer size and complexity of the game I quickly found myself completely immersed in it. '.
			'I made <a href="ao/aoscripts.rar">some scripts</a> that make things easier.'
	),
	'eve' => array(
		'api' => 'https://api.eveonline.com/eve/CharacterInfo.xml.aspx?characterID=1761654327&keyId=898288&vCode='.$keys['eve'],
		'format' => 'xml',
		'about' => '<p><b>EVE Online</b> is a what every MMO should aspire to be; <a href="https://secure.eveonline.com/trial/?invc=f861919b-eb94-437b-80d6-df84d952885f&action=buddy">Another world.</a> '.
			'It will be intimidating for new players as there is no clear path cut out for you, but for those that persist it is very rewarding. '.
			'Supporting the harshest PVP-environment of any MMO available today, this one is certainly not for the faint-hearted. '.
			'<p><a class="external" href="http://o.smium.org/profile/887#pfavorites">Loadouts for my favorite ships.</a>',
	),
	'wow' => array(
		'api' => 'http://eu.battle.net/api/wow/character/outland/destru?locale=en_US&apikey='.$keys['battle.net'],
		'format' => 'json',
		'about' => '<p><b>World of Warcraft</b> has been a guilty pleasure of mine on and off for years. '.
			'So far I\'ve played a couple characters to end-game and messed around with more PvP alts than I can remember. '.
			'Even a die hard science fiction fan like myself must admit that the game is simply breathtakingly well executed.'
	),
	'league' => array(
		'api' => 'https://na.api.pvp.net/api/lol/na/v1.4/summoner/by-name/destru?api_key='.$keys['league'],
		'format' => 'json',
		'about' => '<p><b>League of Legends</b> is an fast-paced, twitchy MOBA with a surprising amount of depth. '.
			'At first the game seemed fun enough, but it wasn\'t until I saw professional play that I fell in love with it. '.
			'My favorite team is <span class="command">CLG</span>. '
	)
);

function api($game, $api) {
	global $staticData;

	if ($game == 'ao') {
		if ($api->name) {
			$cdn = 'http://cdn.funcom.com/billing_files/AO_shop/face/';
			$output = '<table class="fluid">'.
				'<tr><td rowspan="7"><div class="image" style="background-image:url(\''.str_replace('http://www.anarchy-online.com/character/smallphotos/', $cdn, trim($api->smallpictureurl)).'\');width:67px;height:100px;"></div></td></tr>'.
				'<tr><td class="dark">Name</td><td><a href="http://auno.org/ao/equip.php?saveid=177936">'.$api->name->nick.'</a></td></tr>'.
				'<tr><td class="dark">Profession</td><td>'.$api->basic_stats->profession.'</td></tr>'.
				'<tr><td class="dark">Level</td><td>'.$api->basic_stats->profession_title.' ('.$api->basic_stats->level.')</td></tr>'.
				'<tr><td class="dark">Defender</td><td>'.$api->basic_stats->defender_rank.' ('.$api->basic_stats->defender_rank_id.')</td></tr>'.
				'<tr><td class="dark">Faction</td><td>'.$api->basic_stats->faction.'</td></tr>'.
				'<tr><td class="dark">Organization</td><td>'.$api->organization_membership->organization_name.'</td></tr>'.
				'</table>';
		}
		else error('outdatedapi', 1);
	}
	else if ($game == 'eve') {
		if ($api->result->characterName) {
			$output = '<table class="fluid">'.
				'<tr><td rowspan="8"><div class="image" style="background-image:url(\'http://image.eveonline.com/Character/1761654327_128.jpg\');width:128px;height:128px;"></div></td></tr>'.
				'<tr><td class="dark">Name</td><td><a href="http://eve.battleclinic.com/killboard/combat_record.php?type=player&name=Destru+Kaneda">'.$api->result->characterName.'</a></td></tr>'.
				'<tr><td class="dark">Race</td><td>'.$api->result->race.' ('.$api->result->bloodline.')</td></tr>'.
				'<tr><td class="dark">Skills</td><td>'.round(($api->result->skillPoints/1000000), 1).' million</td></tr>'.
				'<tr><td class="dark">Corporation</td><td><a href="http://rzpd.zkillboard.com">'.$api->result->corporation.'</a></td></tr>'.
				'<tr><td class="dark">Security Status</td><td>'.number_format(floatval($api->result->securityStatus), 2).'</td></tr>'.
				'<tr><td class="dark">Last Seen</td><td>'.$api->result->shipTypeName.' in <a href="http://evemaps.dotlan.net/search?q='.$api->result->lastKnownLocation.'">'.$api->result->lastKnownLocation.'</a></td></tr>'.
				'</table>';

			$zkillCache = 'eve.zkill.json';
			get('https://zkillboard.com/api/solo/kills/characterID/1761654327/limit/10/', $zkillCache, 1);
			$zkills = load($zkillCache, 1);
			if ($zkills) {
				$killCount = 0;
				$output .= '<table class="fluid">';
				foreach ($zkills as $kill) {
					$output .= '<tr>'.
						'<td class="dark">'.date("F j, Y", strtotime($kill->killTime)).'</td>'.
						'<td><a class="external" href="https://zkillboard.com/kill/'.$kill->killID.'">'.$kill->victim->characterName.'</a></td>'.
						'<td>'.$kill->victim->corporationName.'</td>'.
						'<td class="light">'.round(($kill->zkb->totalValue/1000000), 1).'m</td>'.
						'</tr>';
					if (++$killCount == 10) break;
				}
				$output .= '</table>';
			}
		}
		else error('outdatedapi', 1);
	}
	else if ($game == 'wow') {
		if ($api->name) {
			foreach($api->titles as $title) if (isset($title->selected)) $currentTitle = $title->name;
			if (isset($currentTitle)) $name = str_replace('%s', $api->name, $currentTitle);
			else $name = $api->name;
			$feed = array_filter($api->feed, function($i) {
				if (in_array($i->type, array('BOSSKILL', 'ACHIEVEMENT'))) return true;
			});
			$feed = array_values($feed);
			for($i = 0; $i < 5; $i++) {
				$title = $feed[$i]->achievement->title;
				$points = $feed[$i]->achievement->points;
				if (!empty($title)) {
					if (!empty($points)) $events[] = $title.' <span class="light">+'.$points.'</span>';
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
		else error('outdatedapi', 1);
	}
	else if ($game == 'tsw') {
		if ($api->name) {
			$actives = ''; $passives = '';
			$iconSize = '24';
			foreach($api->actives as $slot) {
				$actives .= '<div class="image icon light" title="'.$slot->name.'" style="display:inline-block;width:'.$iconSize.'px;height:'.$iconSize.'px;margin-right:5px;padding:2px;">'.
					'<img src="'.$slot->image->icon.'" class="icon" width="'.$iconSize.'" height="'.$iconSize.'">'.
					'</div>';
			}
			foreach($api->passives as $slot) {
				$passives .= '<div class="image icon dark" title="'.$slot->name.'" style="display:inline-block;width:'.$iconSize.'px;height:'.$iconSize.'px;margin-right:5px;padding:2px;">'.
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
		else error('outdatedapi', 1);
	}
	else if ($game == 'league') {
		$cdn = 'http://ddragon.leagueoflegends.com/cdn/'.$staticData->leagueVersion.'/img/profileicon/';
		$stats = 'http://na.op.gg/summoner/userName=destru';

		if ($api->destru->id) {
			$output = '<table class="fluid">'.
				'<tr><td rowspan="8"><div class="image" style="background-image:url(\''.$cdn.$api->destru->profileIconId.'.png\');width:128px;height:128px;"></div></td></tr>'.
				'<tr><td class="dark">Name</td><td><a href="'.$stats.'">'.$api->destru->name.'</a></td></tr>'.
				'<tr><td class="dark">Role</td><td>Jungle / Mid</td></tr>'.
				'<tr><td class="dark">Favorite Champions</td><td>Lee Sin and Ezreal</td></tr>'.
				'<tr><td class="dark">Favorite Players</td><td><s>Doublelift</s> Stixxay and Aphromoo</td></tr>'.
				'<tr><td class="dark">Last Online</td><td>'.date("F j, Y", str_replace('000', '.0', $api->destru->revisionDate)).'</td></tr>'.
				'</table>';
		}
		else error('outdatedapi', 1);
	}
	return $output;
}

$gameList = array_keys($games);
sort($gameList);
if ($command == 'games' || $command == 'game' || in_array($command, $gameList)) {
	if (in_array($command, $gameList)) {
		$option = $command;
		$command = 'game';
	}
	if (isset($option) && !empty($games[$option])) {
		$game = $option;
		print $prompt.$games[$game]['about'];
		if (!empty($games[$game]['api']) && !empty($games[$game]['format'])) {
			$cache = $game.'.'.$games[$game]['format'];
			get($games[$game]['api'], $cache, 1);
			$api = load($cache, 1);
			print api($game, $api);
		}
		$output = true;
	}
	else output('<p class="error">'.$joshua.'Valid options are '.implodeHuman($gameList, true).'.<p class="example">'.$command.' '.$gameList[rand(0,count($gameList)-1)]);
}
?>
