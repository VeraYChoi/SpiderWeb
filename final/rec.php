<!DOCTYPE html>

<html>
    <head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        

        function add_to_watched(i) {
            //console.log("clicked" + i);
            console.log("$('#add_to_watched'):eq(i).html(): " + $("#add_to_watched" + i).html());
            if ($("#add_to_watched" + i).html() == "Add to watched") {
                $("#add_to_watched" + i).html("Remove from watched");
                $("#add_to_watched" + i).css("background-color","red");
                $("#add_to_wishlist" + i).css("display","none");
                $("#fav" + i).css("display","block");
                
            }
            else if ($("#add_to_watched" + i).html() == "Remove from watched") {
   
                $("#add_to_watched" + i).html("Add to watched");
                $("#add_to_watched" + i).css("background-color","#E84855");
                $("#add_to_wishlist" + i).css("display","block");
                $("#fav" + i).html("Add to favourites");
                $("#fav" + i).css("display","none");

            }
        }


        function add_to_fav(i) {
            if ($("#fav" + i).html() == "Add to favourites") {
                console.log("clicked fav")
                $("#fav" + i).html("Remove from favourites");
                $("#fav" + i).css("background-color","red");
            }
            else if ($("#fav" + i).html() == "Remove from favourites") {
                $("#fav" + i).html("Add to favourites");
                $("#fav" + i).css("background-color","#AFE1AF");
            }
        }

        function add_to_wishlist(i) {
            if ($("#add_to_wishlist" + i).html() == "Add to wishlist") {
                $("#add_to_wishlist" + i).html("Remove from wishlist");
                $("#add_to_wishlist" + i).css("background-color","red");
                $("#add_to_watched" + i).css("display","none");
            }
            else if ($("#add_to_wishlist" + i).html() == "Remove from wishlist") {
   
                $("#add_to_wishlist" + i).html("Add to wishlist");
                $("#add_to_wishlist" + i).css("background-color","#00CFC1");
                $("#add_to_watched" + i).css("display","block");

            }
            
        }
    
        
            const wishlist_id = [299534, 299536];
            const watched_id = [];
            var recommend_id = [];
            var similar_id = [];

            async function getJson_recommend(url) {
                let response = await fetch(url);
                let data = await response.json()
                return data;
            }

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
                for (let i = 0; i < watched_id.length; i++) {
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
            }

   



        function output(movie_id, i, title, img_source, genres, overview, date) {
            document.getElementById("show_data").innerHTML += "<div id='movie" + i + "' style='border: 1px solid black'>"
            document.getElementById("movie" + i).innerHTML += "<div id='image_column'> <img id='poster' src='"+"http://image.tmdb.org/t/p/w500/" + img_source + "'> </div>";


            var genre_string="";
            genres.forEach(element => {
                        genre_string += element;
                        genre_string += ", ";
            });
            document.getElementById("movie" + i).innerHTML += "<div id='info_column'><h2 id='title'> " + title + "</h2> <p id='date'> <strong>Release Date: </strong>" + date + "</p><p id='genres'><strong>Genres: </strong>" + genre_string.substring(0,genre_string.length-2) + "</p> <p id='overview'> <strong>Overview: </strong>" + overview + "</p></div>";
            
            
            document.getElementById("movie" + i).innerHTML += "<button onclick=\"showCast(" + i + ")\" id='cast_button'>Click to see cast info!</button><div id=\"list_cast\"><ul id='cast" + i + "'>";
            get_cast_info(movie_id, i);
            document.getElementById("movie" + i).innerHTML += "</ul></div>"
            document.getElementById("movie" + i).innerHTML += "<div id='add_buttons'><button id='add_to_watched" +i + "' onclick='add_to_watched(" + i + ")'>Add to watched</button><button id='add_to_wishlist" + i + "' onclick='add_to_wishlist(" + i + ")'>Add to wishlist</button><button id='fav" + i + "' onclick='add_to_fav(" + i + ")'>Add to favourites</button></div>";
            document.getElementById("show_data").innerHTML += "</div>"
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
                                genres[g] = movie["genres"][g].name;
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
    </head>

    <body>

        <div id="show_data">
            <p id="title"></p>
            <p id="release"></p>
            <p id="description"></p>
            <p id="genres"></p>
            
        </div>

    </body>
</html>

