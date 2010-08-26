<?
// settings
$url = 'http://eu.jumpgateevolution.com/newsfeed_uk.php';
$number = 10;
$secondsBeforeUpdate = 600; // be nice
$timeout = 5;
$cache = 'official_news.xml';
$xml = xml2array(file_get_contents($cache));

// get the xml
if(empty($dev)) {
	if(!file_exists($cache)) touch($cache);
	$lastModified = filemtime($cache);
	if(time() - $lastModified > $secondsBeforeUpdate) {
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
		$urlData = curl_exec($ch);
		if(!empty($urlData)) {
			$handle = fopen($cache, "w");
			fwrite($handle, $urlData);
			fclose($handle);
		}
	}
}

// print it out
print '<div id="eunews">'."\r";
for ( $i = 0; $i < $number; $i++ ) {
	$name = $xml['rss']['_c']['channel']['_c']['item'][$i]['_c']['title']['_v'];
	$link = $xml['rss']['_c']['channel']['_c']['item'][$i]['_c']['link']['_v'];
	$desc = $xml['rss']['_c']['channel']['_c']['item'][$i]['_c']['description']['_v'];
	$date = $xml['rss']['_c']['channel']['_c']['item'][$i]['_c']['pubDate']['_v'];
	print '<div class="news_item"><a href="'.$link.'">'.$name.'</a> <span class="desc">'.$desc.'</span> <span class="date">'.$date.'</span></div>'."\r";
}
print '</div>'."\r";

?>