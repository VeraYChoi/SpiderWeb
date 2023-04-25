<?php
session_start();
?>
<!DOCTYPE html>

<html>

<head>
    <meta charset="utf-8"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type = "image/png" href="images/logo.ico">
    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #FFF8F0;
            margin: 0;
            padding: 0;
        }

        body {
            height: 100%;
            width: 100%;
        }

        #search h1 {
            text-align: center;
            font-size: 50px;
            color: #E84855;
            display: block;
        }

        #search #h3_div {
            padding: 30px;
            width: 400px;
            margin: 0 auto;
            display: block;
        }

        #search h3 {
            display: block;
            text-align: center;
            font-size: 25px;
            color: #001B2E;
        }

        #search {
            //background-color: #E84855;
            width: 1000px;
            margin: 0 auto;
            display: block;
            margin-top: 40px;
        }

        #query {
            color: black;
            background-color: #00CFC140;
            font-size: 30px;
            width:500px;
            border: 0px;
            border-radius: 15px;
            margin: 0 auto;
            display: block;
            margin-top: 30px;
            padding: 20px;
            text-align: center;
            color: #333;
            -webkit-box-shadow: 0 2px 10px 1px rgba(0,0,0,0.5);
            box-shadow: 0 2px 10px 1px rgba(0,0,0,0.5);
        }
        #getMovies {
            font-size: 20px;
            background-color: #00CFC140;
            width: 150px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            border-radius: 15px;
            border-color: #00CFC140;
            margin-bottom: 20px;
        }
        #getMovies:hover {
            -webkit-box-shadow: 0 2px 10px 1px rgba(0,0,0,0.5);
            box-shadow: 0 2px 10px 1px rgba(0,0,0.5,0.5);
        }
        #genre_select {
            font-size: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
            margin-top: 10px;
            margin-bottom: 0 px;
            background-color: #00CFC140;
            width: 150px;
        }
        #poster {
            max-width: 90%;
        }
        @media (max-width: 700px) {
			.movieClass {
                max-width: 90%;
            }
            #overview {
                display: block;
            }
		}
    </style>

    <title>SpiderWeb Search</title>
    <link rel="stylesheet" href="style.css">
</head>



<body>

<?php
    include('./header.php');
    makeHeader('search.php', 'Search');
?>
<div id="search">
    <h1>Welcome to SpiderWeb Movies!</h1>
    <div id="h3_div">
        <h3>Search your favourite movies in seconds and find new ones to watch!</h3>
    </div>
</div>


<div id="show_data">
    <p id="title"></p>
    <p id="release"></p>
    <p id="description"></p>
    <p id="genres"></p>
</div>

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
                    document.getElementById("cast" + i).innerHTML += "<li class='castMembers' id='cast_item0'> No cast information found at this time. </li>";
                }
                else {
                    for (let k = 0; k < cast_length; k++) {
                        if (people["cast"][k].known_for_department == "Acting") {
                            document.getElementById("cast" + i).innerHTML += "<li class='castMembers' id='cast_item" + k + "'> " + people["cast"][k].name + "</li>";
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

    function buildSearch() {
        let URL = "https://api.themoviedb.org/3/genre/movie/list?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US";
        let t = "<input name='query' id='query' type='text' placeholder='Enter search query here'>";
            res = fetch(URL)
                .then (res => res.text())
                .then (data => 
                {
                    t += "<select name='genre_select' id='genre_select' size='1'>";
                    genres = JSON.parse(data)
                    //document.getElementById("search").innerHTML += "<select name='genre_select' size='1'>";
                    t += "<option> All genres </option>";
                    for (let i = 0; i < genres.genres.length; i++) {
                        t += "<option>" + genres.genres[i].name + "</option>";
                    }
                    t += "</select>";
                    t += "<br /><button id='getMovies' onclick='getAPI()'>Submit</button>"
                    document.getElementById("search").innerHTML += t;
                    
                })
                .catch (error => console.log(error))
                
    }

    function output(movie_id, i, title, img_source, genre_array_php, genres, overview, date) {
        document.getElementById("show_data").innerHTML += "<div class = 'movieClass' id='movie" + i + "' style='border: 1px solid black'>"
        document.getElementById("movie" + i).innerHTML += "<div id='image_column'> <img id='poster' src='"+"http://image.tmdb.org/t/p/w500/" + img_source + "'> </div>";
        
        let genre_string = "";
        let count = 1;
        for (var key in genre_array_php) {
            genres.forEach(element => {
                if (genre_array_php[key] == element) {
                    genre_string += key;
                    if(genres[genres.length-1] == element){
                        console.log("Last Element")
                    }
                    else {
                        genre_string += ", ";
                    }
                }
                
            });
        }
        document.getElementById("movie" + i).innerHTML += "<div id='info_column'><h2 id='title'> " + title + "</h2> <p id='date'> <strong>Release Date: </strong>" + date + "</p><p id='genres'><strong>Genres: </strong>" + genre_string + "</p> <p id='overview'> <strong>Overview: </strong>" + overview + "</p></div>";

        document.getElementById("movie" + i).innerHTML += "<button onclick=\"showCast(" + i + ")\" id='cast_button'>Click to see cast info!</button><div id=\"list_cast\"><ul id='cast" + i + "'>";
        get_cast_info(movie_id, i);
        document.getElementById("movie" + i).innerHTML += "</ul></div>"
        //document.getElementById("movie" + i).innerHTML += "</p>";
        document.getElementById("movie" + i).innerHTML += "<div id='add_buttons'><button id='add_to_watched" +i + "' onclick='add_to_watched(" + i + ", " + movie_id + ")'>Add to watched</button><button id='add_to_wishlist" + i + "' onclick='add_to_wishlist(" + i + ", " + movie_id + ")'>Add to wishlist</button><button id='fav" + i + "' onclick='add_to_fav(" + i + ", " + movie_id + ")'>Add to favourites</button></div>";
        document.getElementById("show_data").innerHTML += "</div>"
        var isWatch = false;
        <?php
            if (isset($_SESSION['userid'])){
                echo 'user_id = "'.$_SESSION['userid'].'";';
            }
            else {
                echo 'console.log("what to do here");';
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

    function getAPI() {

        document.getElementById("show_data").innerHTML = "";
        let query = $("#query").val();

        if (query == "") {
            alert("Query is required!")
        }

        else {
            let genre = $('select[name="genre_select"]').val();

            let URL = "https://api.themoviedb.org/3/search/movie?api_key=fcabeffb7c941589973c5ba5beb7f636&query=" + query;

            URL += "&adult=false";

            /* Step 1: Make instance of request object...
            ...to make HTTP request after page is loaded*/
            res = fetch(URL)
                .then (res => res.text())
                .then (data => 
                {
                    console.log(data);
                    console.log(data.lenght);
                    movies = JSON.parse(data)
                    //console.log(Object.keys(movies).lenght);
                    console.log(movies);
                    console.log(movies["results"]);
                    console.log("num: " + movies["total_results"]);
                    console.log("movies[results][0]: " + movies["results"][0]);
                    var genre_array_php = <?= json_encode($genre_name_id) ?>;
                    

                    if (movies["total_results"] == 0) {
                        results = document.getElementById("show_data");
                        results.innerHTML = "<p>No movies found</p>"
                    }
                    else {
                        if (genre == "All genres") {
                            for (let i = 0; i < movies["total_results"]; i++) {
                                let obj = movies["results"][i];
                                let title = obj.title;
                                let genres = obj["genre_ids"];
                                let img_source = obj["poster_path"];
                                let overview = obj["overview"];
                                let date = obj["release_date"];
                                let movie_id = obj["id"];
                            
                                output(movie_id, i, title, img_source, genre_array_php, genres, overview, date);
                            }
                        }
                        
                        else {
                            console.log("movies[\"results\"][2][\"genre_ids\"].lenght: " + movies["results"][2]["genre_ids"]);
                            console.log("watch out");
                            for (let k = 0; k < movies["total_results"]; k++) {
                                let obj = movies["results"][k];
                                obj["genre_ids"].forEach(element => {
                                    if (element == genre_array_php[genre]) {
                                        console.log("in here");
                                        let title = obj.title;
                                        let genres = obj["genre_ids"];
                                        let img_source = obj["poster_path"];
                                        let overview = obj["overview"];
                                        let date = obj["release_date"];
                                        let movie_id = obj["id"];

                                        output(movie_id, k, title, img_source, genre_array_php, genres, overview, date);                                    }
                                });
                            }
                        }
                    }
                })
                .catch (error => console.log(error))                    
        }
    }

    function getGenreArray() {
        const name = [];
        const id = [];
        request = new XMLHttpRequest();
        console.log("1 - request object created");

        request.open("GET", "https://api.themoviedb.org/3/genre/movie/list?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US", true)

        console.log("2 - opened request file");

        // Step 3: set up event handler/callback

        request.onreadystatechange = function() {
        console.log("3 - readystatechange event fired.");

        if (request.readyState == 4 && request.status == 200) {
            // Step 5: wait for done + success
            console.log("5 - response received");
            result = request.responseText;
            genres = JSON.parse(result);
            for (let i = 0; i < genres.genres.length; i++) {
                name.push(genres.genres[i].name);
                id.push(genres.genres[i].id);
            }
            console.log("name: " + name);
            console.log("id: " + id);
        }
        else if (request.readyState == 4 && request.status != 200) {
            console.log("Something is wrong!  Check the logs to see where this went off the rails");
        }

        else if (request.readyState == 3) {
            console.log("Too soon!  Try again");
        }

        console.log("name1: " + name);
        console.log("id1: " + id);
        return name;

        }
        // Step 4: fire off the HTTP request
        request.send();
        console.log("4 - Request sent");
    }
    
    buildSearch();
       
</script>



</body>

</html>
