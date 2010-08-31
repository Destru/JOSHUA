<?php
	// random awesome powers
	$dir = "gallery";
	$d = scandir($dir);
	foreach($d as $file) {
		if(stristr($file, '.jpg') || stristr($file, '.png')) {
			$images[] = $file;
		}
	}
	shuffle($images);
	// images
	print "\r\t".'<div id="slideshow">';
	foreach($images as $image) {
		print "\r\t\t".'<img src="http://binaerpilot.no/resize.php?src=alexander/gallery/'.$image.'&amp;h=265&amp;w=349&amp;zc=1" height="265" width="349" alt="" class="pointer" />';
	}
	print "\r\t".'</div>';
	// pager
	print "\r\t".'<ul class="thumbs">';
	foreach($images as $image) {
		print "\r\t\t".'<li><a href="/alexander/gallery/'.$image.'" class="view" rel="gallery"><img src="http://binaerpilot.no/resize.php?src=alexander/gallery/'.$image.'&amp;h=44&amp;w=54&amp;zc=1" height="44" width="54" alt="" /></a></li>';
	}
	print '</ul>';
?>