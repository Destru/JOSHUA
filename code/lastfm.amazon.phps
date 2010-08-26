<?php
// lame amazon+last.fm hax by alexander <http://binaerpilot.no/alexander/>

$associateID = "myid"; // your associate id
$topArtists = file_get_contents("lastfm.topartists.cache");
$topArtists = utf8_decode($topArtists); // UTF8 h8
$artist = explode("\n", $topArtists);
$number = 10;
for ($i = 0; $i < $number; $i++) {
  $artistArray = explode(",", $artist[$i]);
  $amazonQuery[] = $artistArray[2];
}

$rand = rand(0,9);
print '<iframe src="http://rcm-uk.amazon.co.uk/e/cm?t='.
      $associateID.
      '&o=2&p=6&l=st1&mode=music&search='.
      $amazonQuery[$rand].
      '&=1&fc1=&lt1=&lc1=&bg1=&f=ifr"
      marginwidth="0" marginheight="0"
      width="120" height="150"
      border="0" frameborder="0"
      style="border:none;"
      scrolling="no">
      </iframe>';
?>