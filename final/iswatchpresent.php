<?php
    $server = "35.212.42.21";
    $userid = "uaqtg5oezskik";
    $pw = "talissqluser";
    $db = "db4qzjfvgwun4s";

    

    $conn = new mysqli($server, $userid, $pw);

    if ($conn->connect_error){
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->select_db($db);
    

    $user_id = $_POST["userid"];
    $movie_id = $_POST["movieid"];

   

    $sql = "SELECT MovieId FROM `Watched` WHERE UserId='$user_id' and MovieId=$movie_id";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        echo 'true';
    }
    else {
        echo 'false';
    }
    

    

    mysqli_close($conn);
    


?>