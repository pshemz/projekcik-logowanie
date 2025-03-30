<?php
    session_start();

    if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
        header('Location: logowanie.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuń konto</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <header>
        <a href="main.php">
            <img src="logo.png" alt="Logo Magicbook" id="logo">
        </a>
        
        <nav id="panel">
            <a href="zmien_haslo.php" class="zmien_haslo">Zmień hasło</a>
            <a href="usun_konto.php" class="usun_konto">Usuń konto</a>
            <a href="logout.php" class="logout">Wyloguj się</a>
        </nav>
    </header>

    <form action="usun_konto.php" method="POST">
        <h2>Usuń konto</h2>
        <input type="password" name="haslo" placeholder="Podaj hasło" required><br>
        <input type="password" name="potwierdz_haslo" placeholder="Potwierdź hasło" required><br>
        <input type="submit" value="Usuń konto">
    </form>

    <?php
        $conn = new mysqli("localhost", "root", "", "logowanie1");

        $mail = $_SESSION['mail'];

        if (isset($_POST['haslo'], $_POST['potwierdz_haslo'])) {
            $haslo = $_POST['haslo'];
            $potwierdz_haslo = $_POST['potwierdz_haslo'];

            $query = "SELECT haslo FROM uzytkownicy WHERE mail = '$mail'";
            $wynik = mysqli_query($conn, $query);
            $w = mysqli_fetch_assoc($wynik);
            
            if (!password_verify($haslo, $w['haslo'])) {
                echo "<p style='color: red;'>Błąd! Hasło jest niepoprawne.</p>";
            } 
            elseif ($haslo !== $potwierdz_haslo) {
                echo "<p style='color: red;'>Błąd! Podane hasła się nie zgadzają.</p>";
            } 
            else {
                $query = "DELETE FROM uzytkownicy WHERE mail = '$mail'";
                mysqli_query($conn, $query);

                session_destroy();
                echo "<p style='color: red;'>Pomyślnie usunąłeś konto! </p>".'<a href="main.php">Kliknij tutaj, aby przejść do strony głównej.</a>';
            }
        }
    ?>
</body>
</html>