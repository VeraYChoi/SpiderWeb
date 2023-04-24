<?php
    session_start();
?>
<!DOCTYPE html>

<html>
    <head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <title>Recommendations</title>
    <link rel='stylesheet' href='style.css'>
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
        }

        #cast_button {
        padding: 10px;
        font-size: 1em;
        background-color: #001B2E;
        border: 0px;
        color: #FFF8F0;

        margin:0 auto;
        display:block;
        font-size: 30px;
        margin-bottom: 30px;
    }



    ul {
        display: none;
        grid-template-columns: repeat(4, 1fr);
    }

    #show_data div, p, li, h2, strong, ul {
        background-color: #001B2E;
    }

    #show_data > div {
        border-radius: 20px;
        width: 80%;
        margin-right: auto;
        margin-left: auto;
        margin-bottom: 30px;
        overflow: hidden;
    }
    #show_data p, li, h2 {
        color: #FFF8F0;
    }

    #poster {
        width: 250px;
        margin-top: 30px;
        margin-left: 30px;
        display: block;
    }

    #title {
        color: #E84855;
        margin-top: 50px;
    }

    strong {
        color: #00CFC1;
    }



    #image_column {
        height: 450px;
        width: 40%;
        display: inline-block;

    }

    #info_column {
        height: 450px;
        margin-left: 2%;
        width: 50%;
        display: inline-block;
        
        vertical-align:top;
    }

    #add_buttons button:first-child{
        display: block;
        margin-bottom: 30px;
        border-radius: 30px;
        border: 0px;
        background-color: #E84855;
        font-size: 25px;
        color: #FFF8F0;
        padding: 15px;
        margin:0 auto;
        display:block;
        margin-bottom: 30px;
        width: 300px;

    }

  

    #add_buttons button:nth-child(2) {
        display: block;
        margin-bottom: 30px;
        border-radius: 30px;
        border: 0px;
        background-color: #00CFC1;
        font-size: 25px;
        color: #FFF8F0;
        padding: 15px;
        margin:0 auto;
        margin-bottom: 30px;
        width: 300px;
    }

    #add_buttons button:nth-child(3) {
        display: none;
        margin-bottom: 30px;
        border-radius: 30px;
        border: 0px;
        background-color: #AFE1AF;
        font-size: 25px;
        color: #FFF8F0;
        padding: 15px;
        margin:0 auto;
        margin-bottom: 30px;
        width: 300px;
    }
    </style>
    </head>

    <body>

    <?php
// if (isset($_SESSION['userid'])){
    // $curruser = $_SESSION['userid'];
    // $sql = "SELECT MovieId from Watched WHERE UserId='".$curruser."'";
    
    //establish connection info
    $curruser = $_SESSION['userid'];
    $server = "35.212.42.21";
    $userid = "uaqtg5oezskik";
    $pw = "talissqluser";
    $db = "db4qzjfvgwun4s";
    $conn = new mysqli($server, $userid, $pw, $db);

	$sql = "SELECT MovieId from WatchLater WHERE UserId='".$curruser."'";
    $q = $conn->query($sql);
    $watchedids = [];
    foreach ($q as $rowid=>$rowdata) {
        foreach ($rowdata as $key=>$value) {
            $watchedids[$rowid] = $value;
        }
    }
    $watchedids_json = json_encode($watchedids);

    echo "<script>const watchlist_id = JSON.parse('".$watchedids_json."');</script>";
?>
    <script>

        function get_cast_info(movie_id, i) {
        let URL = "https://api.themoviedb.org/3/movie/"+movie_id+"/credits?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US";
            res = fetch(URL)
                .then (res => res.text())
                .then (data => 
                {
                    var people = JSON.parse(data);
                    var cast_length = Object.keys(people["cast"]).length;
                    //console.log("people lenght: " + cast_length);
                    
                    if (cast_length == 0) {
                        document.getElementById("cast" + i).innerHTML += "<li id='cast_item0'> No cast information found at this time. </li>";
                    }
                    else {
                        for (let k = 0; k < cast_length; k++) {
                            if (people["cast"][k].known_for_department == "Acting") {
                                document.getElementById("cast" + i).innerHTML += "<li id='cast_item" + k + "'> " + people["cast"][k].name + "</li>";
                            }
                        }
                    }
                })
                .catch (error => console.log(error))

    }

    function showCast(i) {
        var x = document.getElementById("cast" + i);
        var button = document.getElementById("cast_button");
        if (button.innerHTML == "Click to see cast info!") {
            x.style.display = "grid";
            button.innerHTML = "Click to close cast info!";
        }
        else {
            x.style.display = "none";
            button.innerHTML = "Click to see cast info!";
        }
    }



    function add_to_watched(i, movie_id) {
            <?php
            if (isset($_SESSION['userid'])){
                echo 'user_id = "'.$_SESSION['userid'].'";';
            }
            else {
                echo 'alert("Please log in to add to your watched list");';
                echo "return;";
            }
            ?>
            //console.log("clicked" + i);
            console.log("$('#add_to_watched'):eq(i).html(): " + $("#add_to_watched" + i).html());
            if ($("#add_to_watched" + i).html() == "Add to watched") {
                $("#add_to_watched" + i).html("Remove from watched");
                $("#add_to_watched" + i).css("background-color","red");
                $("#add_to_wishlist" + i).css("display","none");
                $("#fav" + i).css("display","block");
                $.ajax({
                    url: "addtowatch.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    }

                });
                
            }
            else if ($("#add_to_watched" + i).html() == "Remove from watched") {
   
                $("#add_to_watched" + i).html("Add to watched");
                $("#add_to_watched" + i).css("background-color","#E84855");
                $("#add_to_wishlist" + i).css("display","block");
                $("#fav" + i).html("Add to favourites");
                $("#fav" + i).css("display","none");
                $.ajax({
                    url: "removefromwatch.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    }

                });
                $.ajax({
                    url: "removefromfavs.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    }

                });

            }

            
                
        }


        function add_to_fav(i, movie_id) {
            <?php
            if (isset($_SESSION['userid'])){
                echo 'user_id = "'.$_SESSION['userid'].'";';
            }
            else {
                echo 'alert("Please log in to add to your favorites");';
                echo "return;";
            }
            ?>
            if ($("#fav" + i).html() == "Add to favourites") {
                console.log("clicked fav")
                $("#fav" + i).html("Remove from favourites");
                $("#fav" + i).css("background-color","red");
                $.ajax({
                    url: "addtofaves.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    }

                });
            }
            else if ($("#fav" + i).html() == "Remove from favourites") {
                $("#fav" + i).html("Add to favourites");
                $("#fav" + i).css("background-color","#AFE1AF");
                $.ajax({
                    url: "removefromfavs.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    }

                });
            }

            
        }

        function add_to_wishlist(i, movie_id) {
            <?php
            if (isset($_SESSION['userid'])){
                echo 'user_id = "'.$_SESSION['userid'].'";';
            }
            else {
                echo 'alert("Please log in to add to your wishlist");';
                echo "return;";
            }
            ?>
            if ($("#add_to_wishlist" + i).html() == "Add to wishlist") {
                $("#add_to_wishlist" + i).html("Remove from wishlist");
                $("#add_to_wishlist" + i).css("background-color","red");
                $("#add_to_watched" + i).css("display","none");
                $.ajax({
                    url: "addtowish.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    }

                });
            }
            else if ($("#add_to_wishlist" + i).html() == "Remove from wishlist") {
   
                $("#add_to_wishlist" + i).html("Add to wishlist");
                $("#add_to_wishlist" + i).css("background-color","#00CFC1");
                $("#add_to_watched" + i).css("display","block");
                $.ajax({
                    url: "removefromwish.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    }

                });
            }

            
            
        }
       

            getAPI(watchlist_id);
        





    function output(movie_id, i, title, img_source, genres, overview, date) {
        document.getElementById("show_data").innerHTML += "<div id='movie" + i + "' style='border: 1px solid black'>"
        document.getElementById("movie" + i).innerHTML += "<div id='image_column'> <img id='poster' src='"+"http://image.tmdb.org/t/p/w500/" + img_source + "'> </div>";

        document.getElementById("movie" + i).innerHTML += "<div id='info_column'><h2 id='title'> " + title + "</h2> <p id='date'> <strong>Release Date: </strong>" + date + "</p><p id='genres'><strong>Genres: </strong>" + genres + "</p> <p id='overview'> <strong>Overview: </strong>" + overview + "</p></div>";
        
        document.getElementById("movie" + i).innerHTML += "<button onclick=\"showCast(" + i + ")\" id='cast_button'>Click to see cast info!</button><div id=\"list_cast\"><ul id='cast" + i + "'>";
        get_cast_info(movie_id, i);
        document.getElementById("movie" + i).innerHTML += "</ul></div>"
        document.getElementById("movie" + i).innerHTML += "<div id='add_buttons'><button id='add_to_watched" +i + "' onclick='add_to_watched(" + i + ", " + movie_id + ")'>Add to watched</button><button id='add_to_wishlist" + i + "' onclick='add_to_wishlist(" + i + ", " + movie_id + ")'>Add to wishlist</button><button id='fav" + i + "' onclick='add_to_fav(" + i + ", " + movie_id + ")'>Add to favourites</button></div>";
            document.getElementById("show_data").innerHTML += "</div>"
            var isWatch = false;
            <?php
                        if (isset($_SESSION['userid'])){
                            echo 'user_id = "'.$_SESSION['userid'].'";';
                        }
                        else {
                            echo 'alert("Please log in to add to your wishlist");';
                            echo "return;";
                        }
                    ?>

            $.ajax({
                    url: "iswatchpresent.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    },
                    success: function(response){
                        if (response=='true'){
                            $("#add_to_watched" + i).html("Remove from watched");
                            $("#add_to_watched" + i).css("background-color","red");
                            $("#add_to_wishlist" + i).css("display","none");
                            $("#fav" + i).css("display","block");
                            isWatch = true;
                        }
                    }

        });
        <?php
                    if (isset($_SESSION['userid'])){
                        echo 'user_id = "'.$_SESSION['userid'].'";';
                    }
                    else {
                        echo 'alert("Please log in to add to your wishlist");';
                        echo "return;";
                    }
                    ?>

        $.ajax({
                    url: "isfavepresent.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    },
                    success: function(response){
                        if (response=='true'){
                            $("#fav" + i).html("Remove from favourites");
                            $("#fav" + i).css("background-color","red");
                        }
                    }

        });
        <?php
                    if (isset($_SESSION['userid'])){
                        echo 'user_id = "'.$_SESSION['userid'].'";';
                    }
                    else {
                        echo 'alert("Please log in to add to your wishlist");';
                        echo "return;";
                    }
                    ?>

        $.ajax({
        
            url: "iswishpresent.php",
                    type: "POST",
                    data: {
                        movieid: movie_id,
                        userid: user_id
                    },
                    success: function(response){
                        if (response=='true' && isWatch==false){
                            $("#add_to_wishlist" + i).html("Remove from wishlist");
                            $("#add_to_wishlist" + i).css("background-color","red");
                            $("#add_to_watched" + i).css("display","none");
                        }
                    }

        });
    }
    function getAPI(arr) {

        //const my_array = Array.from(arr);
        console.log("in API " + arr);


        //document.getElementById("show_data").innerHTML = "";
            let k = 0;
            arr.forEach(element => {
                let URL = "https://api.themoviedb.org/3/movie/" + element + "?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US";
                console.log("arr[i]: " + element);
                res = fetch(URL)
                    .then (res => res.text())
                    .then (data => 
                    {
                        movie = JSON.parse(data)

        
                        let title = movie.original_title;
                        let genres = [];
                        for (let g = 0; g < movie["genres"].length; g++) {
                            genres[g] = movie["genres"][g];
                        }
                        
                        let img_source = movie["poster_path"];
                        let overview = movie["overview"];
                        let date = movie["release_date"];
                        let movie_id = movie["id"];

                        output(movie_id, k, title, img_source, genres, overview, date);
                        k++;
                        
                            
                        
                    })
                    .catch (error => console.log(error));
            })
            }

                    
        



        main();
        //console.log("arr is: " + arr);
        //getAPI(arr);

    </script>

        <?php
        include('./header.php');
        makeHeader('watched.php', 'Watched');
        ?>

        <div id="show_data">
            <p id="title"></p>
            <p id="release"></p>
            <p id="description"></p>
            <p id="genres"></p>
            
        </div>

    </body>
</html>

