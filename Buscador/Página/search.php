<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0>
    <style> body {padding: 0; margin: 0;} </style>
    <script src="library/p5.min.js"></script>
    <script src="library/addons/p5.dom.min.js"></script>
    <script src="library/addons/p5.sound.min.js"></script>
    <script src="sketches/psychoTriangle.js"></script>
    <link rel="stylesheet prefetch" href="css/style1.css">
    <link rel="stylesheet prefetch" href="fonts/font1">
    <link rel="stylesheet prefetch" href="css/indexBuscador.css">
    <title><?php echo $_GET['source']?> - Web Crawler</title>
    <link rel="shortcut icon" type="image/x-icon" href="imgs/w-logo.png" />

    <link rel="stylesheet" href="css/social1.css">
    <link rel="stylesheet" href="css/social2.css">
    <link rel="stylesheet" href="css/social3.css">

</head>
<body>
<ul style="position: relative; top: 20px; left: 20px;width: 90%;height: 80px">
    <li><h1e ><spane style="font-family: Open Sans, Helvetica, Arial, sans-serif; top: 5px;position: relative">Web crawler</spane></h1e></li>
    <li style="width: 65%"> <input value="<?php echo $_GET['source']?>" style="position: relative;left: 15px; width: 100%" onchange="search(this)" class="tags-input"></li>
</ul>

<?php
    include ("database/controller.php");
    search($_GET['source']);
?>

<!--
<div style="position: relative; width: 58.5%; left: 225px" class="w3-container w3-card-2 w3-white w3-round-large w3-margin w3-row-padding"><br>
    <a href="https://es.wikipedia.org/wiki/Star_Wars" class="w3-text-blue"><i class="fa fa-paperclip"></i> https://es.wikipedia.org/wiki/Star_Wars</a><br>
    <span><i class="fa fa-key"></i> palabra1, palabra2, palabra3, palabra4.</span>
    <span class="w3-right">Coincidencias: 0</span>
    <h6></h6>
</div>
-->



    <script>
        function search(obj) {
            window.location.href = "search.php?source="+obj.valueOf().value;
        }
    </script>
</body>
</html>
