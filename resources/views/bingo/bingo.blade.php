<!DOCTYPE html>
<html lang="hu" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TJ Bingo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2e2e2e;
            color: #ffffff;
            min-height: 100vh;
            margin: 0;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .container {
            width: 100%;
            max-width: 90vh;
            padding: 10px;
            margin: auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .bingo-board {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 5px;
            margin: 5px auto;
            /* width: 100%; */
        }

        .bingo-cell {
            aspect-ratio: 1;
            padding: 5px;
            text-align: center;
            border: 1px solid #fff;
            cursor: pointer;
            width: 75%;
            height: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 1s ease-in-out;
            font-size: clamp(10px, 1.5vw, 14px);
            word-wrap: break-word;
            overflow-wrap: break-word;
            hyphens: auto;
        }

        h1 {
            margin: 0;
            font-size: clamp(20px, 4vw, 32px);
        }

        .bingo-cell.marked {
            background-color: #29a847bf;
            color: white;
        }

        .bingo-cell.winning {
            border-radius: 10px;
            cursor: not-allowed;
        }

        .flash {
            animation: flash 0.5s;
        }

        #newGameBtn {
            position: fixed;
            bottom: 10px;
            right: 10px;
            z-index: 1000;
            font-size: clamp(12px, 2vw, 16px);
        }

        #scoreBoard {
            position: fixed;
            top: 10px;
            right: 10px;
            background-color: rgba(46, 46, 46, 0.9);
            padding: 8px;
            border-radius: 5px;
            z-index: 1000;
            font-size: clamp(12px, 2vw, 16px);
        }

        #score {
            font-size: clamp(24px, 4vw, 36px) !important;
            margin: 0;
        }

        @media (max-height: 600px) {
            .container {
                max-width: 95vw;
            }
            .bingo-cell {
                font-size: clamp(8px, 1.2vw, 12px);
                padding: 2px;
            }
            .bingo-board {
                gap: 3px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">TJ Bingo</h1>
        <div class="bingo-board" id="bingoBoard"></div>
        <button class="btn btn-primary" id="newGameBtn">Új játék</button>
    </div>
    <div id="scoreBoard">
        <h2 class="text-center">Pontok</h2>
        <p id="score" class="text-center" style="font-size: 48px;">0</p>
    </div>

</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.2.1/js/bootstrap.bundle.min.js"></script>
<script>
    const phrases = [
        "Pediglen", "Kiakad", "Ül csöndben valami megoldást keresve", "Nincs szünet", "Fehér ing",
        "Egyedül Robikának magyaráz valamit", "Az ő megoldása jobb!", "Logikai szerkezet", "Inkrementálás",
        "Csörgő telefon",
        "Hátulgombolós változónév", "Nyomtatott lap", "Lehet hogy igazad van", "Megsértődik", "Nyakkendő",
        "Konstruktor", "Ablakon kinéz", "Miértnem figyelsz?", "Javallom", "Beefel Nándival/Pistivel",
        "Hamarabb el kell menni", "Sanyi kérdezi hogy csinálta", "Undorító angol kiejtés", "Kérdésre nem válaszol",
        "Bajusz simi"
    ];

    $(document).ready(function() {
        const cookieName = 'tjBingo';
        const scoreCookieName = 'tjBingoScore';
        let gameData = getCookie(cookieName);
        let score = parseInt(getCookie(scoreCookieName) || 0);

        if (gameData) {
            gameData = JSON.parse(gameData);
        } else {
            gameData = {
                marked: [],
                winPatterns: [],
                phrases: shuffle(phrases)
            };
            setCookie(cookieName, JSON.stringify(gameData), 10);
        }

        $('#score').text(score);

        const bingoBoard = $('#bingoBoard');

        function createBoard() {
            bingoBoard.empty();
            for (let i = 0; i < gameData.phrases.length; i++) {
                const cell = $('<div>')
                    .addClass('bingo-cell')
                    .text(gameData.phrases[i])
                    .attr('data-index', i)
                    .on('click', function() {
                        const index = $(this).data('index');
                        if (gameData.winPatterns.some(pattern => pattern.includes(index))) {
                            return;
                        }
                        $(this).toggleClass('marked');
                        if (gameData.marked.includes(index)) {
                            gameData.marked = gameData.marked.filter(i => i !== index);
                        } else {
                            gameData.marked.push(index);
                        }
                        setCookie(cookieName, JSON.stringify(gameData), 10);
                        checkWin();
                    });
                if (gameData.marked.includes(i)) {
                    cell.addClass('marked');
                }
                if (gameData.winPatterns.some(pattern => pattern.includes(i))) {
                    cell.addClass('winning');
                }
                bingoBoard.append(cell);
            }
        }

        function shuffle(array) {
            let currentIndex = array.length,
                randomIndex;

            while (currentIndex !== 0) {
                randomIndex = Math.floor(Math.random() * currentIndex);
                currentIndex--;

                [array[currentIndex], array[randomIndex]] = [
                    array[randomIndex], array[currentIndex]
                ];
            }

            return array;
        }

        function setCookie(name, value, hours) {
            const d = new Date();
            d.setTime(d.getTime() + (hours * 60 * 60 * 1000));
            const expires = "expires=" + d.toUTCString();
            document.cookie = name + "=" + value + ";" + expires + ";path=/";
        }

        function getCookie(name) {
            const cname = name + "=";
            const decodedCookie = decodeURIComponent(document.cookie);
            const ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(cname) == 0) {
                    return c.substring(cname.length, c.length);
                }
            }
            return "";
        }

        function checkWin() {
            const marked = gameData.marked;
            const winPatterns = [
                [0, 1, 2, 3, 4],
                [5, 6, 7, 8, 9],
                [10, 11, 12, 13, 14],
                [15, 16, 17, 18, 19],
                [20, 21, 22, 23, 24], // rows
                [0, 5, 10, 15, 20],
                [1, 6, 11, 16, 21],
                [2, 7, 12, 17, 22],
                [3, 8, 13, 18, 23],
                [4, 9, 14, 19, 24], // cols
                [0, 6, 12, 18, 24],
                [4, 8, 12, 16, 20] // diagonals
            ];

            for (let pattern of winPatterns) {
                if (pattern.every(index => marked.includes(index))) {
                    pattern.forEach(index => {
                        const cell = $(`.bingo-cell[data-index=${index}]`);
                        cell.addClass('flash winning').css('cursor', 'not-allowed');
                    });
                    if (!gameData.winPatterns.some(p => JSON.stringify(p) === JSON.stringify(pattern))) {
                        gameData.winPatterns.push(pattern);
                        score++;
                        setCookie(scoreCookieName, score, 10);
                        $('#score').text(score);
                        // alert("Nyertél!");
                    }
                    setCookie(cookieName, JSON.stringify(gameData), 10);
                }
            }
        }

        $('#newGameBtn').on('click', function() {
            gameData = {
                marked: [],
                winPatterns: [],
                phrases: shuffle(phrases)
            };
            setCookie(cookieName, JSON.stringify(gameData), 10);
            createBoard();
        });

        createBoard();
    });
</script>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
</head>

<body>
    <header>
        <!-- place navbar here -->
    </header>
    <main></main>
    <footer>
        <!-- place footer here -->
    </footer>
    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>
