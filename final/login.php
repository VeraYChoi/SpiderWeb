<?php
session_start();
?>
<!doctype html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<title>Log In</title>
	<link rel='stylesheet' href='style.css'>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<link rel="icon" type = "image/png" href="images/logo.ico">
	<style>
		body {text-align: center;}
		p {text-align:center}
	</style>
</head>

<body>

<!-- password for mjain02 is "mypassword" -->
<?php
	include('./header.php');
	makeHeader('login.php', 'Log In');
?>

<?php
	$helptext = "<p class='help'>Don't have an account? <a href='./signup.php'>Sign up here</a>.</p>";
	$errortext = "<p class='error'></p>";
	if ($_POST) {
		//establish connection info
		$server = "35.212.42.21";
		$userid = "uaqtg5oezskik";
		$pw = "talissqluser";
		$db = "db4qzjfvgwun4s";
		$conn = new mysqli($server, $userid, $pw, $db);
	
		$sql = "SELECT username, email FROM users";
		$result = $conn->query($sql);
		$found = false;
		$unem = $_POST['username'];
		$pw = hash("sha256", $_POST['password']);
		foreach ($result as $rowid=>$rowdata) {
			if ($rowdata['username'] == $unem) {
				$found = true;
				$sql = "SELECT password FROM users WHERE username='".$unem."'";
				$result2 = $conn->query($sql);
				foreach ($result2 as $rowid2=>$rowdata2) {
					if ($pw == $rowdata2['password']) {
						$_SESSION['userid'] = $unem;
						if (isset($_GET['origin'])){
							$redirect = $_GET['origin'];
						}
						else {
							$redirect = 'search.php';
						}
						echo '<script type="text/javascript">window.location = "'.$redirect.'"</script>';
					}
					else {
						$errortext = "<p class='error'>Incorrect password.</p>";
					}
				}
			}
			else if ($rowdata['email'] == $unem) {
				$found = true;
				$sql = "SELECT password, username FROM users WHERE email='".$unem."'";
				$result2 = $conn->query($sql);
				foreach ($result2 as $rowid2=>$rowdata2) {
					if ($pw == $rowdata2['password']) {
						$_SESSION['userid'] = $rowdata2['username'];
						if (isset($_GET['origin'])){
							$redirect = $_GET['origin'];
						}
						else {
							$redirect = 'search.php';
						}
						echo '<script type="text/javascript">window.location = "'.$redirect.'"</script>';
					}
					else {
						$errortext = "<p class='error'>Incorrect password.</p>";
					}

				}
			}
		}

		if (!$found) {
			$helptext = "<p class='help'>We don't have an account with this email address or username. Please <a href='./signup.php'>Create an account</a> here.</p>";
		}

		$conn->close();
	}
?>
<div class='ls'>
<?php
	if(isset($_GET['origin'])) {
		$actionurl = 'login.php?origin='.$_GET['origin'];
	}
	else {
		$actionurl = 'login.php';
	}
?>
<form method="post" id="login_form" class='ls' action="
<?php
echo $actionurl;
?>
">
	<label for="un">Username/Email</label>
	<input type='text' name='username' id='un'>
	<label for="pw">Password</label>
	<input type='password' name='password' id='pw'>
	<?php
	echo $errortext;
	?>
	<input type = "submit" value = "Log In" />
</form>
</div>
<?php
	echo $helptext;
?>
<p class='unavailable'></p>

<script>

	form_obj = document.querySelector("#login_form");
	errortext = document.querySelector("p.error");
	helptext = document.querySelector("p.help");
	loggedin = document.querySelector("p.unavailable");
	
	form_obj.onsubmit = function() {
		un = document.querySelector("#un").value;
		pw = document.querySelector("#pw").value;

		if (un=="")
		{
			errortext.innerHTML = "Please enter a username or an email address.";
			return false;
		}
		else if (pw=="")
		{
			errortext.innerHTML = "Please enter a password.";
			return false;
		}

		return true;
	}
	<?php
		if (isset($_SESSION['userid'])) {
			echo "form_obj.style.display = 'none';\n";
			echo "errortext.style.display = 'none';\n";
			echo "helptext.style.display = 'none';\n";
			$p = "<p class='unavailable'>You are already logged in. Search for new movies to watch <a href='./search.php'>here</a>!</p>";
			echo 'loggedin.innerHTML = "'.$p.'";';
		}
	?>
</script>

</div>

</body>
</html>
