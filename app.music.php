<div id="music" class="window">
	<h1>Bleep</h1>
	<ul class="tracks"><?php $path = $_SERVER['DOCUMENT_ROOT'];
$dir = opendir($path.'/alexander/music');
while ($file = readdir($dir)) {
	if(stristr($file, '.mp3')) {
		$tracks[] = $file;
	}
}
shuffle($tracks);
$limit = 10;
for ($i = 0; $i < $limit; $i++) {
	$title = str_replace('binaerpilot - ','',str_replace('_',' ',str_replace('.mp3','',$tracks[$i])));
	print "\r\t\t".'<li><a href="music/'.$tracks[$i].'">'.$title.'</a></li>';
}
print "\r";
closedir($dir); ?>
	</ul>
</div>
