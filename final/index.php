<?php
if (isset($_SESSION['userid'])) {
    header("Location:search.php");
}
else {
    header("Location:login.php");
}
echo "Redirect failed. <a href='./search.php'>Click here.</a>";
?>