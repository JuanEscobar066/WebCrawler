<?php

    function connect(){
        $servername = "localhost";
        $username = "wc";
        $password = "webcrawler";
        $db = "webcrawler";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $db);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    function closeConnection($conn){
        mysqli_close($conn);
    }

    function search($wordList){
        $conn = connect();
        $stringComma = $bodytag = str_replace(" ", ",", $wordList);
        $query =
            " SELECT url , sum(quantity) as coincidence 
              FROM DATA WHERE (";
        $array = explode(" ", $wordList);
        for($i = 0; $i < count($array); $i++){
            $query = $query."WORD LIKE '%$array[$i]%' OR ";
        }
        $query = $query."1 = 2";
        $query = $query.") group by url order by coincidence desc";
        $result = mysqli_query($conn, $query);
        if($result){
            //Resultado encontrado
            while ($row = mysqli_fetch_array($result, 1)){
                echo "
                <div style=\"position: relative; width: 58.5%; left: 225px\" class=\"w3-container w3-card-2 w3-white w3-round-large w3-margin w3-row-padding\"><br>
                    <a href='".$row['url']."' class=\"w3-text-blue\"><i class=\"fa fa-paperclip\"></i> ".$row['url']."</a><br>
                    <span><i class=\"fa fa-key\"></i> ".$stringComma.".</span>
                    <span class=\"w3-right\">Coincidencias: ".$row['coincidence']."</span>
                    <h6></h6>
                </div>
                ";
            }
        } else {
            echo $query;
            echo " Error:".mysqli_errno($conn);
            //No se encontro ninguna coincidencia
        }
        closeConnection($conn);
    }
?>