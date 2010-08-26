<?php // alexander@binaerpilot.no
	$path = $_SERVER['DOCUMENT_ROOT'];
	$dir = $path.'/alexander/gallery';
	$arr = ''; $q = 0; $noTag = 0;
	if (is_dir($dir)) {
		$d = dir($dir);
		while($entry = $d->read())
		if(stristr($entry, '.jpg') || stristr($entry, '.png')) $arr[] = $entry;
		$d->close();
		shuffle($arr);
		// images
		print "\r\t".'<div id="slideshow">';
		while ($q < sizeof($arr)) {
			print "\r\t\t".'<img src="http://binaerpilot.no/resize.php?src=alexander/gallery/'.$arr[$q].'&amp;h=265&amp;w=349&amp;zc=1" height="265" width="349" alt="" class="pointer" />';
			$q++;
		}
		print "\r\t".'</div>';
		$q = 0;
		// pager
		print "\r\t".'<ul class="thumbs">';
		while ($q < sizeof($arr)) {
			print "\r\t\t".'<li><a href="/alexander/gallery/'.$arr[$q].'" class="view" rel="gallery"><img src="http://binaerpilot.no/resize.php?src=alexander/gallery/'.$arr[$q].'&amp;h=44&amp;w=54&amp;zc=1" height="44" width="54" alt="" /></a></li>';
			$q++;
		}
		print '</ul>';
		$dir = '';
	}
?>