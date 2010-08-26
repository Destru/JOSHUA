<? // boxinizer
	$box = array(
		'<a href="http://eu.jumpgateevolution.com/guildsignup/"><img src="'.$site.'images/left-beta.png" alt=""/></a>'
	);
	$count = count($box)-1;
	$random = rand(0,$count);
	print $box[$random];
?>