<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="height=device-height, width=device-width, initial-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="index.css" />
    <link rel="stylesheet" href="index.css" />
    <link href="css/footer.css" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,600,0,0" />
    <title>Lista Ferm</title>
    <style>
        .data-container {
            top: 50px;
        }

        .informacje {
            text-align: center;
        }

        .floating-stack {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 450px;
            height: 550px;
            background: rgba(255, 255, 255, .5);
            padding: 4rem;
            border-radius: 10px;
            text-align: center;
        }

        body {
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            flex: 1;
            position: relative;
        }
    </style>
</head>

<body>
    <?php
session_start();
        if (!isset($_SESSION['user_id'])) {
            
            header("Location: /Hagric/login.html");
            exit();
        }
        ?>
    <div class='container'>
        <div class='floating-stack'>
            <div class="login-card-logo">
                <img src="logo.png" alt="logo">
            </div>
            <div class='list-container'>
                <a href='lista_rolnikow.php'>Lista rolników</a>
                <a href='zgloszenia.php'>Zgłoszenia problemów</a>
                <a href='zgloszenia_leczenia.php'>Zgłoszone leczenia</a>
                <a href='upadki.php'>Upadki</a>
                <a href='/Hagric/logout.php'>Wyloguj</a>
            </div>
        </div>
    </div>
    <div class="kontener">
        <div class="footer">
            <p><span class="footer-text">&copy;</span> <span class="current-year">Year</span> <span class="footer-text">Hagric - Developed by <a href="https://www.ac-it.pl/" target="_blank" class="footer-link">AC IT Sp. z o.o.</a></span></p>
        </div>
    </div>

    <script>
        const currentYear = new Date().getFullYear();
        document.querySelector(".current-year").textContent = currentYear;
    </script>
</body>
</html>
