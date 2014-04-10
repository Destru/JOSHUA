<?php // sudo
if($command == "sudo") {
	if(empty($option)) error('password');
	else {
		if($dump == "sudo make me a sandwich") output('<p class="joshua">'.$joshua.'Okay.</p>');
		else {
			if(hash('sha512', $option) == '289f1f350289cc9493ce6c83d378e068c7c481e3dd8c8572e084dc532468a5e0f462f003612098d11618910294008e063b868edc845cd95a68cee5af3a71fcbf') {
				$_SESSION['sudo'] = 1;
				output('<p class="joshua">'.$joshua.'Authentification successful.</p>');
			}
			else {
				unset($_SESSION['sudo']);
				error('password');
			}
		}
	}
}

// reply
if($command == "reply") {
	if(isset($_SESSION['sudo'])) {
		$storage = "msg.data";
		$message = trim(str_replace($command, '', $dump));
		if(strlen($message) > 0) {
			$fp = fopen($storage, 'a');
			fwrite($fp, date("d/m/y").'^<span class="light">'.$message.'</span>^127.0.0.1'."\n");
			fclose($fp);
			print '<div class="prompt">'.$command.'</div><p class="joshua">'.$joshua.'Reply stored.</p>';
			$output = true;
		}
		else output('<p class="error">'.$joshua.'Reply can\'t be empty.</p>');
	}
	else error('auth');
}

// invalid
if($command == "invalid") {
	if(isset($_SESSION['sudo'])) {
		$storage = "invalid.data";
		if(isset($option) && $option == "clear") {
			$fp = fopen($storage,'w');
			fclose($fp);
			output('<p class="joshua">'.$joshua.'Invalid command log cleared.');
		}
		else {
			$db = dbFile($storage);
			array_unique($db);
			output(implodeHuman($db));
		}
	}
	else error('auth');
}

// *nix
$nix = array('ls', 'ps', 'cd', 'top', 'rm', 'cp', 'who', 'kill', 'll', 'df', 'mkdir',
	'grep', 'man', 'wget', 'rsync', 'cat', 'tail', 'ifconfig', 'ipconfig', 'del',
	'make', 'wget', 'curl', 'pwd', 'dir', 'mysql', 'su', 'netstat', 'login', 'ssh',
	'irssi', 'shutdown');
if(in_array($command, $nix)) {
	if(isset($_SESSION['sudo'])) {
		if($command == "ll") $output = run('ls -al');
		elseif($command == "df") $output = run('df -h');
		elseif($command == "pwd") $output = run('pwd');
		elseif($command == "ifconfig") $output = run('ifconfig');
		elseif($command == "who") $output = run('who');
		elseif($command == "ps") $output = run('ps aux');
		else $output = 'Command not implemented.';
		output('<pre>'.$output.'</pre>');
	}
	else error('auth');
}

?>