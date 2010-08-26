<?php
// lastfm.topartists.php by alexander alexander <http://binaerpilot.no/alexander/>
// the cache code was stolen from an old audioscrobbler script whose author eludes me (mail for cred).

// settings
$lastfmUsername = "astoever";
$lastfmCache = "lastfm.topartists.cache";
$secondsBeforeUpdate = 180; // be nice to their link
$numberOfArtists = 10; // 50 is max
$showFrequency = 1; // set this to 1 if you're a numberfreak
$socketTimeout = 3; // seconds to wait for response from audioscrobbler
$emptyCache = "Cache is empty.";
$wrapperStart = "<ul>";
$wrapperEnd = "</ul>";

// grab the stuff
if(!file_exists($lastfmCache)) touch($lastfmCache);
$lastModified = filemtime($lastfmCache);
if(time() - $lastModified > $secondsBeforeUpdate) {
  @ini_set("default_socket_timeout", $socketTimeout);
  $topArtists = @file_get_contents("http://ws.audioscrobbler.com/1.0/user/$lastfmUsername/topartists.txt");
  if(strlen($topArtists) == 1) {
    touch($lastfmCache);
  }
  else {
    $handle = fopen($lastfmCache, "w");
    fwrite($handle, $topArtists);
    fclose($handle);
  }
}
// post the info
$cacheSize = filesize($lastfmCache);
if($cacheSize < 5) echo $emptyCache;
else {
  $topArtists = file_get_contents($lastfmCache);
  $topArtists = utf8_decode($topArtists); // UTF8 h8
  echo $wrapperStart;
  $artist = explode("\n", $topArtists);
  for ($i = 0; $i < $numberOfArtists; $i++) {
    $artistArray = explode(",", $artist[$i]);
    echo "<li>".$artistArray[2]; if(!empty($showFrequency)) echo " [".$artistArray[1]."]"; echo "</li>";
  }
  echo $wrapperEnd;
}
?>