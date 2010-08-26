<?php
// dotedit.php by alexander <http://binaerpilot.no/alexander/>
// a stripped to the bone content editor

// settings
$refreshDelay = 1; // sets the refresh delay
$createFiles = 1; // 1 to enable file creation (be careful with this function, see below)
$haxFiles = array('php','asp','pl','perl','cgi'); // set the filetypes you _don't_ want users to create here

// misc
$refresh = "<meta http-equiv=\"refresh\" content=\"".$refreshDelay.";URL=".$currentFile."\" />";
$props = "<strong>.edit</strong> <a href=\"http://code.binaerpilot.no/dotedit.phps\">download source</a>"; // feel free to remove
$scriptFile = $_SERVER["SCRIPT_NAME"]; $parts = explode('/', $scriptFile); $scriptFile = $parts[count($parts) - 1];
$filename = $_POST['filename']; $content = $_POST['content']; $submit = $_POST['submit'];
$hax = array('..', '/', '\\', $scriptFile); // prevents lame hacking
$haxCheck = 0; $fileCheck = 0;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<head>
<title>.edit</title>
<? if(!empty($content)) echo $refresh; ?>
</head>

<body>
<div id="main">
<?php
if (empty($content)) {
  echo "<form action=\"$scriptFile\" method=\"post\">";
  echo "<div class=\"form\">filename:<br />";
  echo "<input type=\"text\" name=\"filename\" /> <input type=\"submit\" name=\"submit\" value=\"edit\" />";
  if ($createFiles == 1) echo " <input type=\"submit\" name=\"submit\" value=\"create\" />";
  echo "</div></form>";
}

if ($submit == "edit") {
// security
  foreach ($hax as $q) { if (strpos($filename, $q) !== false) { $haxCheck = 1; break; }}
  if ($haxCheck == "1") { echo "lamer.<br/>".$props; die; }
  if (!file_exists($filename)) { echo "no such file.<br />".$props; die; }
// open
  echo $filename;
  $handle = fopen($filename, "r");
  $contents = fread($handle, filesize($filename));
  fclose($handle);
  echo "<form action=\"$scriptFile\" method=\"post\">";
  echo "<textarea name=\"content\" cols=\"125\" rows=\"20\">".$contents."</textarea>";
  echo "<input name=\"filename\" type=\"hidden\" value=\"$filename\"><br /><br />";
  echo "<input type=\"submit\" name=\"submit\" value=\"process\" />";
  echo "</form>";
}

if ($submit == "create") {
// security
  foreach ($hax as $q) { if (strpos($filename, $q) !== false) { $haxCheck = 1; break; }}
  if ($haxCheck == "1") { echo "lamer.<br/>".$props; die; }
  foreach ($haxFiles as $q) { if (strpos($filename, ".".$q) !== false) { $fileCheck = 1; break; }}
  if ($fileCheck == "1") { echo "not allowed for security reasons.<br/>".$props; die; }
// create
  if (!file_exists($filename)) { touch($filename); echo "file was created.<br />"; }
  else echo "file already exists.<br />";
}

if ($submit == "process") {
// update
  $content = stripslashes($content);
  if (is_writable($filename)) {
    $handle = fopen($filename, 'w');
    fwrite($handle, $content);
    echo $filename." was updated.<br />";
    fclose($handle);
  }
  else echo "no access to <em>$filename</em>.<br />";
}
echo $props;
?>
</div>
</body>
</html>
