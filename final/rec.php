<?php
    session_start();
?>
<!DOCTYPE html>

<html>
    <head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type = "image/png" href="images/logo.ico"></link>
    <title>Recommendations</title>
    <link rel='stylesheet' href='style.css'>
    <style>
        #btn {
          display: none;
          position: fixed;
          bottom: 20px;
          right: 30px;
          z-index: 99;
          border: none;
          outline: none;
          background-color: #E84855;
          color: white;
          cursor: pointer;
          padding: 15px;
          border-radius: 15px;
          font-size: 18px;
        }

        #btn:hover {
          background-color: #00CFC1;
        }
    </style>
    </head>

    <body>
        
        <?php
$url = 'https://api.themoviedb.org/3/genre/movie/list?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US';
$collection_name = 'genres';
$request_url = $url . '/' . $collection_name;
$curl = curl_init($request_url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, [
  'X-genres-Host: api.themoviedb.org/3/genre/movie/list?api_key=',
  'X-genres-Key: fcabeffb7c941589973c5ba5beb7f636',
  'Content-Type: application/json'
]);
$response = curl_exec($curl);
$decoded_json = json_decode($response, true);
$genres = $decoded_json['genres'];
$genre_name_id = array();
foreach($genres as $genre) {
    $genre_name_id[$genre['name']] = $genre['id'];
}

curl_close($curl);

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
                    document.getElementById("cast" + i).innerHTML += "<li class = 'castMembers' id='cast_item0'> No cast information found at this time. </li>";
                }
                else {
                    for (let k = 0; k < cast_length; k++) {
                        if (people["cast"][k].known_for_department == "Acting") {
                            document.getElementById("cast" + i).innerHTML += "<li class = 'castMembers' id='cast_item" + k + "'> " + people["cast"][k].name + "</li>";
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

    <?php
    if (isset($_SESSION['userid'])) {
        $curruser = $_SESSION['userid'];
        
        //establish connection info
        $server = "35.212.42.21";
        $userid = "uaqtg5oezskik";
        $pw = "talissqluser";
        $db = "db4qzjfvgwun4s";
        $conn = new mysqli($server, $userid, $pw, $db);
    
        $sql = "SELECT MovieId from WatchLater WHERE UserId='".$curruser."'";

        $q = $conn->query($sql);
        $wishids = [];
        foreach ($q as $rowid=>$rowdata) {
            foreach ($rowdata as $key=>$value) {
                $wishids[$rowid] = $value;
            }
        }
        $wishids_json = json_encode($wishids);
    
        $sql = "SELECT MovieId from Watched WHERE UserId='".$curruser."'";
        $q = $conn->query($sql);
        $watchedids = [];
        foreach ($q as $rowid=>$rowdata) {
            foreach ($rowdata as $key=>$value) {
                $watchedids[$rowid] = $value;
            }
        }
        $watchedids_json = json_encode($watchedids);
    
        echo "const wishlist_id = JSON.parse('".$wishids_json."');";
        echo "const watched_id = JSON.parse('".$watchedids_json."');";
    }
    else {
        echo "const wishlist_id = [0];";
        echo "const watched_id = [0];";
    }
    ?>

    // const wishlist_id = [299534, 299536];
    // const watched_id = [];
    
    var similar_id = [];
        
    async function produce_output() {
        // const wishlist_id = [299534];
        // const watched_id = [299536];
        const arr = wishlist_id.concat(watched_id);
        console.log("arr lenght: + arr.length);
        if (arr.length == 0) {
            document.getElementById("show_data").innerHTML += "Please add more movies to your wishlist and watchlist to see your personalized movie recommendation list!";
        }
        else {
            console.log("wishlist: " + wishlist_id);
            console.log("watched: " + watched_id);
            console.log("arr: " + arr);
            const rec_arr = [];
            console.log("rec arr lenght: " + rec_arr.length);
            let i = 0, page = 1, k = 0;
            while(rec_arr.length < 10)
            {
                let apiUrl = "https://api.themoviedb.org/3/movie/" + arr[i] + "/recommendations?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US&page=" + page;

                let response = await fetch(apiUrl);
                console.log("response: " + response);
                let my_data = await response.json()
                console.log("my data: ", my_data);
                var obj = my_data["results"];
                console.log("obj: " + obj);

                for (let k = 0; k < obj.length; k++) {
                    rec_arr.push(obj[k]["id"]);
                    console.log("recommend id so far: " + rec_arr);
                    if (page < my_data["total_pages"]) {
                        page++;
                    }
                    else {
                        i++;
                    }
                    let title = obj[k]["original_title"];
                    console.log("title: " + title);
                    let genres = [];
                    for (let g = 0; g < obj[k]["genre_ids"].length; g++) {
                        genres[g] = obj[k]["genre_ids"][g];
                    }

                    let img_source = obj[k]["poster_path"];
                    let overview = obj[k]["overview"];
                    let date = obj[k]["release_date"];
                    let movie_id = obj[k]["id"];
                    output(movie_id, k, title, img_source, genres, overview, date);
                }
            }
        }
    }
        


    function output(movie_id, i, title, img_source, genres, overview, date) {
        document.getElementById("show_data").innerHTML += "<div id='movie" + i + "' style='border: 1px solid black'>"
       
        var genre_array_php = <?= json_encode($genre_name_id) ?>;
        let genre_string = "";
        for (var key in genre_array_php) {
            genres.forEach(element => {
                if (genre_array_php[key] == element) {
                    genre_string += key;
                    genre_string += ", ";
                }
            });
        }
        
        document.getElementById("movie" + i).innerHTML += "<div id='image_column'> <img id='poster' src='"+"http://image.tmdb.org/t/p/w500/" + img_source + "'> </div>";

        document.getElementById("movie" + i).innerHTML += "<div id='info_column'><h2 id='title'> " + title + "</h2> <p id='date'> <strong>Release Date: </strong>" + date + "</p><p id='genres'><strong>Genres: </strong>" + genre_string.substring(0,genre_string.length-2) + "</p> <p id='overview'> <strong>Overview: </strong>" + overview + "</p></div>";
        
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
                echo 'document.write("ttttttttttttttttttttttttttttt")';
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

    //main();
    //console.log("arr is: " + arr);
    //getAPI(arr);

    </script>

    <?php
        include('./header.php');
        makeHeader('rec.php', 'Recommendations');

        if (isset($_SESSION['userid'])) {
            echo "<script>produce_output();</script>";
        }
        else {
            echo "<p class='unavailable'>This page is only available to logged in users. Please <a href='./login.php'>Log In</a> here.</p>";
        }
    ?>

    <div id="show_data">
        <p id="title"></p>
        <p id="release"></p>
        <p id="description"></p>
        <p id="genres"></p>
        
    </div>
        
        <button onclick="scroll_top()" id="btn" title="Go to top">Top</button>

<script>
// Get the button
let mybutton = document.getElementById("btn");

// When the user scrolls down 20px from the top of the document, show the button
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    mybutton.style.display = "block";
  } else {
    mybutton.style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function scroll_top() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
}
</script>

    </body>
</html>

