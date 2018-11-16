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
    <title>Web Crawler</title>
    <link rel="shortcut icon" type="image/x-icon" href="imgs/w-logo.png" />
</head>
<body>

<main>
    <pe>Buscador por palabras</pe>
    <input onchange="search(this)" class="tags-input">
    <h1e><spane>Web crawler</spane></h1e>
</main>

<script>
    function search(obj) {
        window.location.href = "search.php?source="+obj.valueOf().value;
    }

</script>
</body>
</html>
