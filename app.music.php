<div id="music" class="window">
	<h1>Bleep</h1>
	<ul class="tracks"><?php
	// can't stop the rock
	$dir = "music";
	$d = scandir($dir);
	foreach($d as $file) {
		if(stristr($file, '.mp3')) {
			$tracks[] = $file;
		}
	}
	shuffle($tracks);
	$limit = 10;
	for ($i = 0; $i < $limit; $i++) {
		$title = str_replace('binaerpilot - ','',str_replace('_',' ',str_replace('.mp3','',$tracks[$i])));
		print "\r\t\t".'<li><a href="'.$dir.'/'.$tracks[$i].'">'.$title.'</a></li>';
	}
	print "\r";
?></ul>
</div>
