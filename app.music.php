<div id="music" class="window">
	<h1>MP3 Player</h1>
<?php // can't stop the rock
	$dir = "music";
	$d = scandir($dir);
	foreach($d as $file) {
		if(stristr($file, '.mp3')) {
			$tracks[] = $file;
		}
	}
	shuffle($tracks);
	print "\t".'<ul class="menu">';
	for ($i = 0; $i < count($tracks); $i++) {
		$title = str_replace('binaerpilot - ','',str_replace('_',' ',str_replace('.mp3','',$tracks[$i])));
		print "\n\t\t".'<li><a href="'.$dir.'/'.$tracks[$i].'">'.$title.'</a></li>';
	}
	print "\n\t".'</ul>'."\n";
?>
</div>
