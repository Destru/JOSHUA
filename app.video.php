<div id="video" class="window">
	<h1>Video Player</h1>
	<input type="search" placeholder="Search..." name="search">
</div>
<script type="text/javascript">
	$(function(){
		var url = 'http://gdata.youtube.com/feeds/api/videos?max-results=10&alt=json&q=';
		$('#video').on('keyup', 'input[type=search]', function(){
			var query = $(this).val()+', long',
			videos = new Array();
			$.getJSON(url+query+'&callback=?', function(data){
				$.each(data.feed.entry, function(i, entry){
					var id = entry.id.$t.replace('http://gdata.youtube.com/feeds/api/videos/',''),
					url = 'http://www.youtube.com/embed/'+id;
					videos.push(url);
				});
				var video = videos[0];
				if($('#video').has('iframe').length == 0){
					$('#video').append('<iframe src="'+video+'" width="560" height="315" frameborder="0" allowfullscreen>')		
				}
				else {
					 $('#video iframe').attr('src', video);
				}
			});
		});
	});
</script>