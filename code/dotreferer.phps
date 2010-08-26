<?php
// dotreferer.php by alexander alexander <http://binaerpilot.no/alexander/>
// warning: vulnerable to referer exploits (do not make this data public)

// settings
$refererDb = "dotreferer.html";
$refererStore = 50; // how many to store (unlimited)
$server = $_SERVER['SERVER_NAME']; // ignore local refs, set to "your domain" if you have problems
$timeStamp = date("F j, Y, g:i a");
$props = "<strong>.referer</strong> <a href=\"http://www.astoever.no/code/dotreferer.phps\">download source</a>";

// new referer
if(!empty($_SERVER['HTTP_REFERER'])) {
  $referer = $_SERVER['HTTP_REFERER'];
// check for local ref
  $pos = strpos($referer, $server);
  if ($pos === false) {
// read db
    if(!file_exists($refererDb)) touch($refererDb);
    $handle = fopen($refererDb, "r");
    $refererData = fread($handle, filesize($refererDb));
    fclose($handle);
// add referer
    $refererData = "<a href=\"".$referer."\">".$referer."</a> ".$timeStamp."<br />".$refererData;
    $refererArray = explode("<br />", $refererData); $referers = "";
    $count = count($refererArray); if ($count < $refererStore) $refererStore = $count;
    for ($i = 0; $i < $refererStore; $i++) {
      $referers .= $refererArray[$i]."<br />";
    }
// write db
    $handle = fopen($refererDb, "w");
    $pos = strpos($referers, $props);
    if ($pos === false) { $referers = $referers."<br />".$props; fwrite($handle, $referers); }
    else { fwrite($handle, $referers); }
    fclose($handle);
  }
}
?>