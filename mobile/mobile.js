$(document).ready(function(){
	function makeRisk(){
		var lines = $('#risk').height(), 
		lineCount = Math.round(lines/1),
		colorScaler = Math.round(255/lineCount),
		color = 0;
		for(i=0; i < lineCount; i++){
			$('#risk').append('<div class="line" style="background-color:rgb(255, '+color+', 100);" />');
			$('.line:last').animate({width:"100%"}, 1500);
			color = color+colorScaler;
		}
	}
	makeRisk();
});