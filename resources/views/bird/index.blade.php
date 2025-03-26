<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brutalist Flappy Bird</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --bg-color: #000;
            --text-color: #fff;
            --game-bg-color: #111;
            --border-color: #fff;
            --bird-color: #ff0;
            --pipe-color: #fff;
            --pipe-cap-color: #ddd;
            --pipe-border-color: #aaa;
        }

        [data-bs-theme="light"] {
            --bg-color: #f8f9fa;
            --text-color: #212529;
            --game-bg-color: #e9ecef;
            --border-color: #212529;
            --bird-color: #ffc107;
            --pipe-color: #28a745;
            --pipe-cap-color: #218838;
            --pipe-border-color: #1e7e34;
        }

        /* Brutalist CSS */
        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: monospace;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            overflow: hidden;
            transition: background-color 0.3s ease;
        }

        #game-container {
            position: relative;
            width: 400px;
            height: 600px;
            border: 8px solid var(--border-color);
            overflow: hidden;
            background-color: var(--game-bg-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        #bird {
            position: absolute;
            width: 50px;
            height: 35px;
            left: 100px;
            top: 250px;
            transform: rotate(10deg);
            transition: transform 0.1s;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 35"><path d="M45,10c-2.6,0.1-5.2,0.5-7.7,1.4c-1.6,0.6-3.1,1.4-4.3,2.5c-1.2,1.1-2.1,2.5-2.5,4.1c-0.2,0.8-0.2,1.6-0.1,2.4 c0.1,0.8,0.3,1.6,0.6,2.3c-1.8,0.3-3.5,0.9-5.1,1.8c-1.6,0.9-3,2-4.2,3.4c-0.6,0.7-1.2,1.5-1.6,2.3c-0.4,0.8-0.8,1.7-1,2.6 c-0.1,0.4-0.1,0.9-0.1,1.3c0,0.4,0.1,0.9,0.2,1.3c-1.4-0.4-2.9-0.6-4.4-0.6c-1.5,0-3,0.2-4.4,0.6c-1.4,0.4-2.8,1-4,1.8 c-1.2,0.8-2.3,1.8-3.2,2.9c-0.9,1.1-1.6,2.4-2.1,3.7c-0.5,1.3-0.7,2.7-0.7,4.1c0,1.4,0.3,2.8,0.7,4.1c0.5,1.3,1.2,2.6,2.1,3.7 c0.9,1.1,2,2.1,3.2,2.9c1.2,0.8,2.6,1.4,4,1.8c1.4,0.4,2.9,0.6,4.4,0.6c1.5,0,3-0.2,4.4-0.6c1.4-0.4,2.8-1,4-1.8 c1.2-0.8,2.3-1.8,3.2-2.9c0.9-1.1,1.6-2.4,2.1-3.7c0.5-1.3,0.7-2.7,0.7-4.1c0-0.4,0-0.9-0.1-1.3c-0.1-0.4-0.2-0.9-0.3-1.3 c1.4,0.1,2.9,0,4.3-0.3c1.4-0.3,2.8-0.8,4.1-1.5c1.3-0.7,2.5-1.5,3.5-2.5c1-1,1.9-2.1,2.6-3.4c0.7-1.2,1.2-2.6,1.5-4 c0.3-1.4,0.4-2.8,0.3-4.3c-0.1-1.4-0.4-2.8-0.9-4.2c-0.5-1.3-1.2-2.6-2-3.7c-0.8-1.1-1.8-2.1-2.9-3c-1.1-0.8-2.3-1.5-3.6-2 C47.8,10.3,46.4,10,45,10z" fill="%23ffc107"/><circle cx="40" cy="15" r="3" fill="%23212529"/><path d="M35,20c0,0,5,2,8,0" stroke="%23212529" stroke-width="1.5" fill="none"/></svg>');
            background-size: contain;
            background-repeat: no-repeat;
            z-index: 10;
        }

        .pipe {
            position: absolute;
            width: 80px;
            background-color: var(--pipe-color);
            transition: background-color 0.3s ease;
            border-left: 4px solid var(--pipe-border-color);
            border-right: 4px solid var(--pipe-border-color);
            background-image: linear-gradient(to right, 
                rgba(0,0,0,0.1) 0%, 
                rgba(0,0,0,0) 20%, 
                rgba(0,0,0,0.1) 40%, 
                rgba(0,0,0,0) 60%, 
                rgba(0,0,0,0.1) 80%, 
                rgba(0,0,0,0) 100%);
        }

        .pipe-cap {
            position: absolute;
            width: 90px;
            height: 20px;
            background-color: var(--pipe-cap-color);
            left: -5px;
            border-radius: 4px;
            border: 4px solid var(--pipe-border-color);
            z-index: 5;
        }

        .pipe-cap::before {
            content: '';
            position: absolute;
            width: 10px;
            height: 10px;
            background-color: var(--pipe-cap-color);
            border-radius: 50%;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .pipe-top .pipe-cap {
            bottom: -10px;
        }

        .pipe-bottom .pipe-cap {
            top: -10px;
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
            background-color: var(--bg-color);
            border: 4px solid var(--border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
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
            border: 5px solid var(--border-color);
            transition: border-color 0.3s ease;
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
            background-color: var(--bg-color);
            padding: 20px;
            border: 5px solid var(--border-color);
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        button {
            background-color: var(--border-color);
            color: var(--bg-color);
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            margin-top: 20px;
            font-family: monospace;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        button:hover {
            background-color: var(--text-color);
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

        #theme-switch-container {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 30;
        }

        .form-check-input:checked {
            background-color: var(--border-color);
            border-color: var(--border-color);
        }
    </style>
</head>
<body>
    <!-- Theme Switch -->
    <div id="theme-switch-container">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="themeSwitch">
            <label class="form-check-label" for="themeSwitch">Light Mode</label>
        </div>
    </div>

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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
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
        const themeSwitch = document.getElementById('themeSwitch');
        const themeSwitchLabel = document.querySelector('label[for="themeSwitch"]');

        // Theme functions
        function setTheme(theme) {
            document.documentElement.setAttribute('data-bs-theme', theme);
            localStorage.setItem('theme', theme);
            
            if (theme === 'light') {
                themeSwitch.checked = true;
                themeSwitchLabel.textContent = 'Dark Mode';
            } else {
                themeSwitch.checked = false;
                themeSwitchLabel.textContent = 'Light Mode';
            }
        }

        // Check for saved theme preference
        function loadTheme() {
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                setTheme(savedTheme);
            }
        }

        // Theme switch event listener
        themeSwitch.addEventListener('change', function() {
            if (this.checked) {
                setTheme('light');
            } else {
                setTheme('dark');
            }
        });

        // Load theme on page load
        window.addEventListener('load', loadTheme);

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
                if (pipe.topCapElement) pipe.topCapElement.remove();
                if (pipe.bottomCapElement) pipe.bottomCapElement.remove();
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
            
            // Create top pipe cap
            const topCap = document.createElement('div');
            topCap.className = 'pipe-cap';
            topPipe.appendChild(topCap);
            
            // Create bottom pipe
            const bottomPipe = document.createElement('div');
            bottomPipe.className = 'pipe pipe-bottom';
            bottomPipe.style.height = bottomHeight + 'px';
            bottomPipe.style.left = '400px';
            gameContainer.appendChild(bottomPipe);
            
            // Create bottom pipe cap
            const bottomCap = document.createElement('div');
            bottomCap.className = 'pipe-cap';
            bottomPipe.appendChild(bottomCap);
            
            // Add pipes to the array
            pipes.push({
                topElement: topPipe,
                bottomElement: bottomPipe,
                topCapElement: topCap,
                bottomCapElement: bottomCap,
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
                    birdPosition + 35 > gameContainer.offsetHeight - parseInt(pipe.bottomElement.style.height) &&
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