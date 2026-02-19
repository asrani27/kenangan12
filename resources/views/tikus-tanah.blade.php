<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Game Pukul Tikus Tanah</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background: #87CEEB;
        }

        h1 {
            margin-top: 20px;
        }

        #game {
            width: 360px;
            margin: 20px auto;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        .hole {
            width: 100px;
            height: 100px;
            background: #654321;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
        }

        .mole {
            width: 80px;
            height: 80px;
            background: #8B4513;
            border-radius: 50%;
            position: absolute;
            bottom: 10px;
            left: 10px;
            display: none;
        }

        .active .mole {
            display: block;
        }

        #info {
            font-size: 18px;
            margin-top: 10px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h1>🐹 Pukul Tikus Tanah</h1>

    <div id="info">
        Skor: <span id="score">0</span> |
        Waktu: <span id="time">30</span> detik
    </div>

    <button id="start">Mulai Game</button>

    <div id="game"></div>

    <script>
        $(document).ready(function(){

    let score = 0;
    let timeLeft = 30;
    let gameInterval;
    let countdown;

    // Buat 9 lubang
    for(let i = 0; i < 9; i++){
        $("#game").append('<div class="hole"><div class="mole"></div></div>');
    }

    function randomHole(){
        $(".hole").removeClass("active");
        let randomIndex = Math.floor(Math.random() * 9);
        $(".hole").eq(randomIndex).addClass("active");
    }

    function startGame(){
        score = 0;
        timeLeft = 30;
        $("#score").text(score);
        $("#time").text(timeLeft);

        gameInterval = setInterval(randomHole, 800);

        countdown = setInterval(function(){
            timeLeft--;
            $("#time").text(timeLeft);

            if(timeLeft <= 0){
                clearInterval(gameInterval);
                clearInterval(countdown);
                $(".hole").removeClass("active");
                alert("Game Selesai! Skor kamu: " + score);
            }
        }, 1000);
    }

    $("#start").click(function(){
        clearInterval(gameInterval);
        clearInterval(countdown);
        startGame();
    });

    $(".hole").click(function(){
        if($(this).hasClass("active")){
            score++;
            $("#score").text(score);
            $(this).removeClass("active");
        }
    });

});
    </script>

</body>

</html>