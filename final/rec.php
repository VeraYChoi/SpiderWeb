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

    $sql = "SELECT MovieId from Watched WHERE UserId='".$curruser."'";
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

    echo "<script>const wishlist_id = JSON.parse('".$wishids_json."');";
    echo "const watched_id = JSON.parse('".$watchedids_json."');</script>";
// }
// else {
//     echo "<p class='unavailable'>This page is only available to logged in users. Please <a href='./login.php'>Log In</a> here.</p>";
// }
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
        // const wishlist_id = [299534, 299536];
        // const watched_id = [];
        var recommend_id = [];
        var similar_id = [];
        
        async function produce_output() {
            var arr = wishlist_id.concat(watched_id);
            console.log("wishlist: " + wishlist_id);
            console.log("watched: " + watched_id);
            console.log("arr: " + arr);
            var rec_arr = [];
            let i = 0, page = 0, k = 0;
            while(rec_arr.lenght < 10)
            {
                let apiUrl = "https://api.themoviedb.org/3/movie/" + arr[i] + "/recommendations?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US&page=" + page;
                
                var my_data = await getJson_recommend(apiUrl);
                var obj = my_data["results"];

                for (let k = 0; k < obj.length; k++) {
                    recommend_id.push(obj[k]["id"]);
                }
                if (page < my_data["total_pages"]) {
                    page++;
                }
                else {
                    i++;
                }
                let title = obj.original_title;
                console.log("title: " + title);
                let genres = [];
                for (let g = 0; g < obj["genres"].length; g++) {
                    genres[g] = obj["genres"][g];
                }

                let img_source = obj["poster_path"];
                let overview = obj["overview"];
                let date = obj["release_date"];
                let movie_id = obj["id"];
                output(movie_id, k, title, img_source, genres, overview, date);
                k++;
            }
        }
        

        async function getJson_recommend(url) {
            let response = await fetch(url);
            let data = await response.json()
            return data;
        }
        
        
        produce_output();
/*
        async function main_recommend(page) {
            for (let i = 0; i < wishlist_id.length; i++) {
                let apiUrl = "https://api.themoviedb.org/3/movie/" + wishlist_id[i] + "/recommendations?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US&page=" + 1;
                
                var my_data = await getJson_recommend(apiUrl);
                var obj = my_data["results"];
                for (let k = 1; k <= my_data["total_pages"]; k ++) {
                    let apiUrl = "https://api.themoviedb.org/3/movie/" + wishlist_id[i] + "/recommendations?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US&page=" + k;
                
                    var my_data = await getJson_recommend(apiUrl);
                    var obj = my_data["results"];

                    for (let k = 0; k < obj.length; k++) {
                        recommend_id.push(obj[k]["id"]);
                    }
                }
            }
            for (let i = 0; i < 
            .length; i++) {
                let apiUrl = "https://api.themoviedb.org/3/movie/" + watched_id[i] + "/recommendations?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US&page=" + 1;
                
                var my_data = await getJson_recommend(apiUrl);
                var obj = my_data["results"];
                for (let k = 1; k <= my_data["total_pages"]; k ++) {
                    let apiUrl = "https://api.themoviedb.org/3/movie/" + watched_id[i] + "/recommendations?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US&page=" + k;
                
                    var my_data = await getJson_recommend(apiUrl);
                    var obj = my_data["results"];

                    for (let k = 0; k < obj.length; k++) {
                        recommend_id.push(obj[k]["id"]);
                    }
                }
            }

            return recommend_id;
            
        }
        
        async function getJson_similar(url) {
            let response = await fetch(url);
            let data = await response.json()
            return data;
        }

        async function main_similar(page) {
            for (let i = 0; i < wishlist_id.length; i++) {
                var apiUrl = "https://api.themoviedb.org/3/movie/" + wishlist_id[i] + "/similar?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US&page=" + page;
                
                var my_data = await getJson_recommend(apiUrl);
                var obj = my_data["results"];
                    for (let k = 0; k < obj.length; k++) {
                        similar_id.push(obj[k]["id"]);
                    }
            }
            for (let i = 0; i < watched_id.length; i++) {
                let apiUrl = "https://api.themoviedb.org/3/movie/" + watched_id[i] + "/similar?api_key=fcabeffb7c941589973c5ba5beb7f636&language=en-US&page=" + page;
                
                var my_data = await getJson_recommend(apiUrl);
                var obj = my_data["results"];


                    for (let k = 0; k < obj.length; k++) {
                        //console.log("pushing: " + obj[k]["id"]);
                        similar_id.push(obj[k]["id"]);
                    }
                }

            return similar_id;
        }



        async function main() {
            while (recommend_id.length < 30) {
                await main_recommend(1);
            }

            console.log("recommend: " + recommend_id);

            let uniqueRec = [...new Set(recommend_id)];
            
            
            let page_num = 1;
            
            while (uniqueRec.length < 40) {
                await main_similar(page_num);
                uniqueRec = uniqueRec.concat(similar_id);
                uniqueRec = [...new Set(uniqueRec)];
                page_num++;
            }
            console.log("final rec here: " + uniqueRec);
            console.log("final rec size here: " + uniqueRec.length);

            var whole_array = watched_id.concat(wishlist_id);
            console.log("whole array size: " + whole_array);

            for (let t = 0; t < whole_array.length; t++) {
                for (let f = 0; f < uniqueRec.length; f++) {
                    if (whole_array[t] == uniqueRec[f]) {
                        const x = uniqueRec.splice(f, 1);
                    }
                }
            }

            
            console.log("similar: " + similar_id);
            console.log("final rec after: " + uniqueRec);
            console.log("final rec size now: " + uniqueRec.length);

            function findDuplicates(arr) {
                return arr.filter((currentValue, currentIndex) =>
                arr.indexOf(currentValue) !== currentIndex);
            }

            //const array = [...uniqueRec];

            getAPI(uniqueRec);
        }*/





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

                    
        



        //main();
        //console.log("arr is: " + arr);
        //getAPI(arr);

    </script>

        <?php
        include('./header.php');
        makeHeader('rec.php', 'Recommendations');
        ?>

        <div id="show_data">
            <p id="title"></p>
            <p id="release"></p>
            <p id="description"></p>
            <p id="genres"></p>
            
        </div>

    </body>
</html>

