<div id="music" class="window">
	<h1>MP3 Player</h1>
<?php
	get('http://binaerpilot.no/albums.json', 'binaerpilot.json');
	$albums = load('albums.json');
	if ($albums) {
		$r = rand(0,count($albums)-1);
		print '<ul class="menu">';
		foreach ($albums[$r]->tracks as $track) {
			print '<li><a href="'.$track->url.'" class="mp3">'.$track->title.'</a>';
		}
		print '</ul>';
		print '<a class="link" href="http://binaerpilot.no/'.$albums[$r]->folder.'/'.$albums[$r]->safeTitle.'"></a>';
	}
	else {
		print '<a href="http://binaerpilot.no">API not responding.</a>';
	}
?>
</div>
