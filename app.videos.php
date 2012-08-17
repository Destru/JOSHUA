<div id="videos" class="window">
	<h1>Video Player</h1>
	<input id="ytSearch" type="search" placeholder="Search..." class="clearfix">
</div>
<script type="text/javascript">
	$(function() {
		var url = 'http://gdata.youtube.com/feeds/api/videos?v=2&max-results=20&duration=long&alt=json&q=';
		$('#ytSearch').on('keyup', function() {
			var query = $(this).val();
			$.getJSON(url+query+'&callback=?', function(data) {
				var videos = new Array(), menu = '';
				$.each(data.feed.entry, function(i, entry) {
					var id = entry.id.$t.replace('tag:youtube.com,2008:video:',''),
					title = entry.title.$t;
					menu = menu+'<li id="'+id+'"><a class="button" href="http://www.youtube.com/watch?v='+id+'">'+title+'</a></li>';
					videos.push([id,title]);
				});
				if($('#videos').has('iframe').length == 0) $('#videos').append('<iframe src="" width="560" height="315" frameborder="0" allowfullscreen style="display:none"/>');
				if($('#videos').has('ul').length == 0) $('#videos').append('<ul class="menu">'+menu+'</ul>');
				else $('#videos ul').html(menu);
				$('#videos ul li').bind('click', function(e) {
					e.preventDefault();
					$('#videos .playing').removeClass('playing');
					var id = $(this).attr('id');
					$(this).find('a').addClass('playing');
					$('#videos iframe').attr('src', 'http://www.youtube.com/embed/'+id).show();
				});
			});
		});
	});
</script>