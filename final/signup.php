<?php
session_start();
?>
<!doctype html>
<html>
<head>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<title>Create Account</title>
	<link rel='stylesheet' href='style.css'>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<link rel="icon" type = "image/png" href="images/logo.ico">
</head>

<body>

<?php
	include('./header.php');
	makeHeader('signup.php', 'Sign Up');
?>

<?php
	$helptext = "<p id='help'>Already have an account? <a href='./login.php'>Log in here</a>.</p>";
	$errortext = "<p id='error'></p>";
	if ($_POST) {
		//establish connection info	
		$server = "35.212.42.21";
		$userid = "uaqtg5oezskik";
		$pw = "talissqluser";
		$db = "db4qzjfvgwun4s";
		$conn = new mysqli($server, $userid, $pw, $db);

		$sql = "SELECT username, email FROM users";
		$result = $conn->query($sql);
		$continue = true;
		$un = $_POST['username'];
		$em = $_POST['email'];
		$pw = hash("sha256", $_POST['password']);
		foreach ($result as $rowid=>$rowdata) {
			if ($rowdata['username'] == $un) {
				$helptext = "<p id='help'>This username already exists. Please choose a different username or <a href='./login.php'>Log In</a> here.</p>";
				$continue = false;
			}
			if ($rowdata['email'] == $em) {
				$helptext = "<p id='help'>An account with this email address already exists. Please <a href='./login.php'>Log In</a> here.</p>";
				$continue = false;
			}
		}
		if ($continue)
			{
				$sql = "INSERT INTO `users`(`id`, `username`, `password`, `email`) VALUES ('DEFAULT','".$un."','".$pw."','".$em."')";
				// echo $sql;
				$result = $conn->query($sql);
				echo "<script>alert('Thank you! Your account has been created.');";
				echo "window.location = 'login.php'</script>";
			}		

		$conn->close();

	}

?>
<div class='ls'>
<form method="post" id="signup_form" class='ls' action="signup.php">
	<label for="em">Email</label>
	<input type='text' name='email' id='em'>
	<label for="un">Username</label>
	<input type='text' name='username' id='un'>
	<label for="pw">Password</label>
	<input type='password' name='password' id='pw'>
	<?php
	echo $errortext;
	?>
	<input type = "submit" value = "Create Account" />
</form>
<?php
echo $helptext;
?>

<script>
	form_obj = document.querySelector("#signup_form");
	helptext = document.querySelector("#help");
	errortext = document.querySelector("#error");
	

	form_obj.onsubmit = function() {

		em = document.querySelector("#em").value;
		un = document.querySelector("#un").value;
		pw = document.querySelector("#pw").value;

		
		// validate name is entered

		if (em=="")
		{
			errortext.innerHTML = "Please enter an email address.";
			return false;
		}
		if (un=="")
		{
			errortext.innerHTML = "Please enter a username.";
			return false;
		}
		else if (pw=="")
		{
			errortext.innerHTML = "Please enter a password.";
			return false;
		}
		else if (!(/^\w+(\.\w+)*@[a-zA-Z]+\.[a-zA-Z]+(\.[a-zA-Z]+)*$/.test(em))) {
			errortext.innerHTML = "Please enter a valid email address.";
			return false;
		}
		else if ((/\W/.test(un))) {
			errortext.innerHTML = "Your username cannot contain special characters other than '_'.";
			return false;
		}
		else if (pw.length<8) {
			errortext.innerHTML = "Your password must be at least 8 characters long.";
			return false;
		}

		return true;
	}
</script>

</div>

</body>
</html>
