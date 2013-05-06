<div id="music" class="window">
	<h1>MP3 Player</h1>
<?php
	get('http://binaerpilot.no/albums.json', 'binaerpilot.json');
	$albums = load('binaerpilot.json');
	if ($albums) {
		$r = rand(0,count($albums)-1);
		print '<ul class="menu">';
		// random album
		foreach ($albums[$r]->tracks as $track) {
			print '<li><a href="'.$track->url.'" class="mp3">'.$track->title.'</a><li>';
		}
		print '</ul>';
	}
	else {
		print '<a href="http://binaerpilot.no">API not responding.</a>';
	}
?>
</div>
