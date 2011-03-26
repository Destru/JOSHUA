<div id="gallery" class="window">
	<h1>Slick Gallery</h1>
	<div class="slideshow">
<?php // slick gallery
	$d = scandir("gallery");
	foreach($d as $file) {
		if(stristr($file, '.jpg') || stristr($file, '.png')) {
			$images[] = $file;
		}
	}
	shuffle($images);
	// images
	foreach($images as $image) {
		print '<div class="slide"><img src="gallery/'.$image.'" width="560" height="345" alt="" /></div>';
	}
?>
	</div>
</div>
<script type="text/javascript">
$(function() {
	// slideshow with pager
	$('#gallery .slideshow').after('<ul class="thumbs"/>').cycle({
	    speed:  500,
	    timeout: 5000,
		delay: 2000,
		pause: true,
		pauseOnPagerHover: true,
	    pager: '.thumbs', 
	    pagerAnchorBuilder: function(idx, slide) {
			var item = '<img src="'+$(slide).find('img').attr('src')+'" width="58" height="36" />';
			if(idx % 10 == 0) {
				return '<li class="noMargin">'+item+'</li>'; 
			}
			else return '<li>'+item+'</li>'; 
	    }
	});
});
</script>