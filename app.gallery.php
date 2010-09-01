<div id="gallery" class="window">
	<h1>Gallery Random</h1>
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
		print '<div id="slideshow">';
		foreach($images as $image) {
			print '<img src="http://binaerpilot.no/resize.php?src=/alexander/gallery/'.$image.'&amp;h=265&amp;w=349&amp;zc=1" height="265" width="349" alt="" class="pointer" />';
		}
		print '</div>';
		// pager
		print '<ul class="thumbs">';
		foreach($images as $image) {
			print '<li><a href="gallery/'.$image.'" class="view" rel="gallery"><img src="http://binaerpilot.no/resize.php?src=/alexander/gallery/'.$image.'&amp;h=44&amp;w=54&amp;zc=1" height="44" width="54" alt="" /></a></li>';
		}
		print '</ul>';
	?>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('#slideshow').cycle({ 
		fx: 'fade', 
		next: '#slideshow',
		timeout: 3000
	});
	$('#gallery').append('<div class="clear"/>');
});
</script>