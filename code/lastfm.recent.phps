<?php
// lastfm.recent.php by alexander <http://binaerpilot.no/alexander/>
// the cache code was stolen from an old audioscrobbler script whose author eludes me (mail for cred).

// settings
$lastfmUsername = "astoever";
$lastfmCache = "lastfm.recent.cache";
$secondsBeforeUpdate = 180; // be nice to their link
$numberOfSongs = 10; // 10 is max
$socketTimeout = 3; // seconds to wait for response from audioscrobbler
$emptyCache = "Cache is empty.";
$wrapperStart = "<ul>";
$wrapperEnd = "</ul>";

// grab the stuff
if(!file_exists($lastfmCache)) touch($lastfmCache);
$lastModified = filemtime($lastfmCache);
if(time() - $lastModified > $secondsBeforeUpdate) {
  @ini_set("default_socket_timeout", $socketTimeout);
  $recentlyPlayedSongs = @file_get_contents("http://ws.audioscrobbler.com/1.0/user/$lastfmUsername/recenttracks.txt");
  if(strlen($recentlyPlayedSongs) == 1) {
    touch($lastfmCache);
  }
  else {
    $handle = fopen($lastfmCache, "w");
    fwrite($handle, $recentlyPlayedSongs);
    fclose($handle);
  }
}
// post the info
$cacheSize = filesize($lastfmCache);
if($cacheSize < 5) echo $emptyCache;
else {
  $recentlyPlayedSongs = file_get_contents($lastfmCache);
  $recentlyPlayedSongs = utf8_decode($recentlyPlayedSongs); // UTF8 h8
  echo $wrapperStart;
  $track = explode("\n", $recentlyPlayedSongs);
  for ($i = 0; $i < $numberOfSongs; $i++) {
    $trackArray = explode(",", $track[$i]);
    echo "<li>".$trackArray[1]."</li>";
  }
  echo $wrapperEnd;
}
?>
