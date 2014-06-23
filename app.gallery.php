<div id="gallery" class="window">
	<h1>Image Gallery</h1>
	<div id="slick">
		<div class="slideshow"></div>
	</div>
</div>
<script>
	function galleryInit() {
		$('#slick .thumbs').remove();
		$('.slideshow').before('<ul class="thumbs"/>').cycle({
			speed:  500,
			timeout: 2000,
			delay: 0,
			pause: true,
			pauseOnPagerHover: true,
			pager: '.thumbs',
			pagerAnchorBuilder: function(idx, slide) {
				var item = '<img src="'+$(slide).find('img').attr('src')+'" width="41" height="41">';
				return '<li>'+item+'</li>';
	    }
		});
	}
	$(function() {
		var adjust;
		$('#slick').hover(function() {
			adjust = $(this).find('ul').height()+10;
			$(this).find('ul').animate({
				'top': '-='+adjust+'px'
			});
		}, function() {
			$(this).find('ul').animate({
				'top': '+='+adjust+'px'
			});
		});
	});
</script>
