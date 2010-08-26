<?php
// IGN shoutcast plugin by alexander <http://binaerpilot.no/alexander/>
// used to broadcast shoutcast details to ingame chat in Anarchy Online

$reply = "(guild) Shoutcast Stream";

// shoutcast
$scip = "localhost";
$scport = "1337";
$scpass = "admin";
$maxlisteners = "16";
$bitrate = "128kpbs/stereo";

// most of this code is stolen from some nullsoft employee

$scfp = fsockopen("$scip", $scport, &$errno, &$errstr, 30);
if($scsuccs!=1){
 fputs($scfp,"GET /admin.cgi?pass=$scpass&mode=viewxml HTTP/1.0\r\nUser-Agent: SHOUTcast Song Status (Mozilla Compatible)\r\n\r\n");
 while(!feof($scfp)) {
  $page .= fgets($scfp, 1000);
 }
 $loop = array("STREAMSTATUS", "BITRATE", "SERVERTITLE", "CURRENTLISTENERS");
 $y=0;
 while($loop[$y]!=''){
  $pageed = ereg_replace(".*<$loop[$y]>", "", $page);
  $scphp = strtolower($loop[$y]);
  $$scphp = ereg_replace("</$loop[$y]>.*", "", $pageed);
  if($loop[$y]==SERVERGENRE || $loop[$y]==SERVERTITLE || $loop[$y]==SONGTITLE || $loop[$y]==SERVERTITLE)
   $$scphp = urldecode($$scphp);
  $y++;
 }
 $pageed = ereg_replace(".*<SONGHISTORY>", "", $page);
 $pageed = ereg_replace("<SONGHISTORY>.*", "", $pageed);
 $songatime = explode("<SONG>", $pageed);
 $r=1;
 while($songatime[$r]!=""){
  $t=$r-1;
  $playedat[$t] = ereg_replace(".*<PLAYEDAT>", "", $songatime[$r]);
  $playedat[$t] = ereg_replace("</PLAYEDAT>.*", "", $playedat[$t]);
  $song[$t] = ereg_replace(".*<TITLE>", "", $songatime[$r]);
  $song[$t] = ereg_replace("</TITLE>.*", "", $song[$t]);
  $song[$t] = urldecode($song[$t]);
  $dj[$t] = ereg_replace(".*<SERVERTITLE>", "", $page);
  $dj[$t] = ereg_replace("</SERVERTITLE>.*", "", $pageed);
$r++;
 }
fclose($scfp);
}

// let's format this junk

if($streamstatus == "1") {

$blob ='(grey)Stream: #L "(lightblue)'.$servertitle.'" "/start http://'.$scip.':'.$scport.'/listen.pls"

(grey)Listeners: (white)'.$currentlisteners.' / '.$maxlisteners.' (grey)['.$bitrate.']
(grey)Right now: (white)'.$song[0].'
(grey)Previously: (white)'.$song[1].'
(grey)02: (white)'.$song[2].'
(grey)03: (white)'.$song[3].'
(grey)04: (white)'.$song[4].'
(grey)05: (white)'.$song[5].'

(grey)#L "(white)Sourcecode" "/start http://www.astoever.no/ao/shoutcast.phps"';
}

// server down :(
else {
  $blob = ' (white)Stream currently offline, please try later.';
}

// send the information through the bot
send($sender,$reply,$blob);
$noreply = 1;
?>
