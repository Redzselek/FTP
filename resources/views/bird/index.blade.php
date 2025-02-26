<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brutalist Flappy Bird</title>
    <style>
        /* Brutalist CSS */
        body {
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #fff;
            font-family: monospace;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
        }

        #game-container {
            position: relative;
            width: 400px;
            height: 600px;
            border: 8px solid #fff;
            overflow: hidden;
            background-color: #111;
        }

        #bird {
            position: absolute;
            width: 50px;
            height: 50px;
            background-color: #ff0;
            left: 100px;
            top: 250px;
            transform: rotate(0deg);
            transition: transform 0.1s;
        }

        .pipe {
            position: absolute;
            width: 80px;
            background-color: #fff;
        }

        .pipe-top {
            top: 0;
        }

        .pipe-bottom {
            bottom: 0;
        }

        #score-container {
            display: flex;
            justify-content: space-between;
            width: 400px;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #000;
            border: 4px solid #fff;
        }

        #score {
            font-size: 40px;
            font-weight: bold;
            text-align: right;
            flex: 1;
        }

        #high-score {
            font-size: 24px;
            font-weight: bold;
            text-align: left;
            flex: 1;
        }

        #game-over {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            display: none;
            z-index: 20;
            background-color: #f00;
            padding: 20px;
            border: 5px solid #fff;
        }

        #start-screen {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 32px;
            font-weight: bold;
            text-align: center;
            z-index: 20;
            background-color: #000;
            padding: 20px;
            border: 5px solid #fff;
        }

        button {
            background-color: #fff;
            color: #000;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            margin-top: 20px;
            font-family: monospace;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #ddd;
        }

        #title {
            font-size: 40px;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        #instructions {
            font-size: 18px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div id="score-container">
        <div id="high-score">HIGH: 0</div>
        <div id="score">0</div>
    </div>
    <div id="game-container">
        <div id="bird"></div>
        <div id="start-screen">
            <div id="title">BRUTALIST FLAPPY BIRD</div>
            <div id="instructions">PRESS SPACE OR CLICK TO FLAP</div>
            <button id="start-button">START GAME</button>
        </div>
        <div id="game-over">
            GAME OVER
            <div id="final-score">SCORE: 0</div>
            <button id="restart-button">RESTART</button>
        </div>
    </div>

    <script>
        // Game variables
        let gameStarted = false;
        let gameOver = false;
        let score = 0;
        let highScore = 0;
        let gravity = 0.5;
        let velocity = 0;
        let birdPosition = 250;
        let pipes = [];
        let pipeInterval;
        let animationFrame;

        // DOM elements
        const bird = document.getElementById('bird');
        const gameContainer = document.getElementById('game-container');
        const scoreDisplay = document.getElementById('score');
        const highScoreDisplay = document.getElementById('high-score');
        const finalScoreDisplay = document.getElementById('final-score');
        const gameOverScreen = document.getElementById('game-over');
        const startScreen = document.getElementById('start-screen');
        const startButton = document.getElementById('start-button');
        const restartButton = document.getElementById('restart-button');

        // Cookie functions for high score
        function saveHighScore(score) {
            if (score > highScore) {
                highScore = score;
                // Set cookie that expires in 1 year
                const date = new Date();
                date.setFullYear(date.getFullYear() + 1);
                document.cookie = `flappyHighScore=${highScore}; expires=${date.toUTCString()}; path=/`;
            }
        }

        function getHighScore() {
            const cookies = document.cookie.split(';');
            for (let i = 0; i < cookies.length; i++) {
                const cookie = cookies[i].trim();
                if (cookie.startsWith('flappyHighScore=')) {
                    return parseInt(cookie.substring('flappyHighScore='.length));
                }
            }
            return 0;
        }

        // Event listeners
        startButton.addEventListener('click', startGame);
        restartButton.addEventListener('click', restartGame);
        document.addEventListener('keydown', function(event) {
            if (event.code === 'Space') {
                if (!gameStarted && !gameOver) {
                    startGame();
                } else if (gameStarted && !gameOver) {
                    flap();
                }
            }
        });
        gameContainer.addEventListener('click', function() {
            if (gameStarted && !gameOver) {
                flap();
            }
        });

        // Game functions
        function startGame() {
            // Load high score from cookie
            highScore = getHighScore();
            highScoreDisplay.textContent = `HIGH: ${highScore}`;
            
            gameStarted = true;
            gameOver = false;
            score = 0;
            velocity = 0;
            birdPosition = 250;
            pipes = [];
            startScreen.style.display = 'none';
            gameOverScreen.style.display = 'none';
            scoreDisplay.textContent = '0';
            
            // Create pipes at regular intervals
            pipeInterval = setInterval(createPipe, 1500);
            
            // Start the game loop
            cancelAnimationFrame(animationFrame);
            animationFrame = requestAnimationFrame(update);
        }

        function restartGame() {
            // Clear all pipes from the DOM
            pipes.forEach(pipe => {
                pipe.topElement.remove();
                pipe.bottomElement.remove();
            });
            startGame();
        }

        function flap() {
            velocity = -8;
            bird.style.transform = 'rotate(-20deg)';
            setTimeout(() => {
                bird.style.transform = 'rotate(0deg)';
            }, 100);
        }

        function createPipe() {
            // Gap between pipes
            const gap = 200;
            // Random height for top pipe
            const topHeight = Math.random() * (gameContainer.offsetHeight - gap - 100) + 50;
            const bottomHeight = gameContainer.offsetHeight - topHeight - gap;
            
            // Create top pipe
            const topPipe = document.createElement('div');
            topPipe.className = 'pipe pipe-top';
            topPipe.style.height = topHeight + 'px';
            topPipe.style.left = '400px';
            gameContainer.appendChild(topPipe);
            
            // Create bottom pipe
            const bottomPipe = document.createElement('div');
            bottomPipe.className = 'pipe pipe-bottom';
            bottomPipe.style.height = bottomHeight + 'px';
            bottomPipe.style.left = '400px';
            gameContainer.appendChild(bottomPipe);
            
            // Add pipes to the array
            pipes.push({
                topElement: topPipe,
                bottomElement: bottomPipe,
                passed: false,
                x: 400
            });
        }

        function update() {
            if (gameOver) return;
            
            // Apply gravity to bird
            velocity += gravity;
            birdPosition += velocity;
            bird.style.top = birdPosition + 'px';
            
            // Check if bird hits the ground or ceiling
            if (birdPosition <= 0 || birdPosition >= gameContainer.offsetHeight - bird.offsetHeight) {
                endGame();
                return;
            }
            
            // Update and check pipes
            for (let i = 0; i < pipes.length; i++) {
                const pipe = pipes[i];
                pipe.x -= 2;
                pipe.topElement.style.left = pipe.x + 'px';
                pipe.bottomElement.style.left = pipe.x + 'px';
                
                // Check for collision
                if (
                    birdPosition < parseInt(pipe.topElement.style.height) &&
                    pipe.x < 150 && pipe.x + 80 > 100 ||
                    birdPosition + 50 > gameContainer.offsetHeight - parseInt(pipe.bottomElement.style.height) &&
                    pipe.x < 150 && pipe.x + 80 > 100
                ) {
                    endGame();
                    return;
                }
                
                // Check if bird passed the pipe
                if (!pipe.passed && pipe.x < 50) {
                    pipe.passed = true;
                    score++;
                    scoreDisplay.textContent = score;
                }
                
                // Remove pipes that are off screen
                if (pipe.x < -80) {
                    gameContainer.removeChild(pipe.topElement);
                    gameContainer.removeChild(pipe.bottomElement);
                    pipes.splice(i, 1);
                    i--;
                }
            }
            
            // Rotate bird based on velocity
            bird.style.transform = velocity > 5 ? 'rotate(20deg)' : 'rotate(-20deg)';
            
            // Continue the game loop
            animationFrame = requestAnimationFrame(update);
        }

        function endGame() {
            gameOver = true;
            clearInterval(pipeInterval);
            
            // Save high score
            saveHighScore(score);
            
            // Update display
            finalScoreDisplay.textContent = `SCORE: ${score}`;
            if (score === highScore && score > 0) {
                finalScoreDisplay.textContent += ' - NEW HIGH SCORE!';
            }
            gameOverScreen.style.display = 'block';
        }
        
        // Load high score on page load
        window.addEventListener('load', function() {
            highScore = getHighScore();
            highScoreDisplay.textContent = `HIGH: ${highScore}`;
        });
    </script>
</body>
</html>