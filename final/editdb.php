<?php
session_start();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>SpiderWeb Movies - Sign Up</title>
<style>
</style>
<link rel='stylesheet' href='style.css'>
</head>

<body>

<?php
//establish connection info

include('./header.php');
makeHeader('editdb.php', 'Testing');

$server = "35.212.42.21";
$userid = "uaqtg5oezskik";
$pw = "talissqluser";
$db = "db4qzjfvgwun4s";
$conn = new mysqli($server, $userid, $pw, $db);

function disp_query($q)
{
	echo "<table><tr>";
	$fields = $q->fetch_fields();
	foreach ($fields as $f) {
		echo "<th>".$f->name."</th>";
	}
	echo "</tr>";
	foreach ($q as $rowid=>$rowdata) {
		echo "<tr>";
		foreach ($rowdata as $key=>$value) {
			echo "<td>$value</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}


$tables = $conn->query("SHOW TABLES");
foreach ($tables as $table) {
	foreach ($table as $key=>$tname){
		echo "<h2>".$tname."</h2>";
		$d = $conn->query("DESCRIBE $tname");
		disp_query($d);
		$t = $conn->query("SELECT * FROM $tname");
		disp_query($t);
		
	}
}

$sql = "";
// $sql = "CREATE TABLE users (`id` INT(20) PRIMARY KEY AUTO_INCREMENT, `username` VARCHAR(255) UNIQUE, `email` VARCHAR(255) UNIQUE, `password` VARCHAR(255));";
// $sql = "ALTER TABLE WatchLater ADD `id` INT(20) PRIMARY KEY AUTO_INCREMENT FIRST";
// $sql = "ALTER TABLE Watched ADD `id` INT(20) PRIMARY KEY AUTO_INCREMENT FIRST";
// $sql = "ALTER TABLE Favorites ADD `id` INT(20) PRIMARY KEY AUTO_INCREMENT FIRST";
// $sql = "INSERT INTO `Watched`(`id`, `UserId`, `MoveId`, `Review`, `Favorite`) VALUES (DEFAULT,'mjain02','22222','this movie was amazing', '1')";
// $sql = "INSERT INTO `Favorites`(`id`, `UserId`, `MoveId`) VALUES (DEFAULT,'mjain02','22222')";
// $sql = "INSERT INTO `Favorites`(`id`, `UserId`, `MoveId`) VALUES (DEFAULT,'mjain02','22222')";
// consistent dtype sizes
// drop empty user, unused cols

$x = $conn->query($sql);
disp_query($x);

?>
	


</body>
</html>
