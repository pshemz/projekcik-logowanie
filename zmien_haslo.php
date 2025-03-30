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
    <title>Zmień hasło</title>
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

    <form action="zmien_haslo.php" method="POST">
        <h2>Zmień hasło</h2>
        <input type="password" name="stare_haslo" placeholder="Stare hasło" required><br>
        <input type="password" name="nowe_haslo" placeholder="Nowe hasło" required><br>
        <input type="password" name="potwierdz_haslo" placeholder="Potwierdź nowe hasło" required><br>
        <input type="submit" value="Zmień hasło">
    </form>

    <?php
        $conn = new mysqli("localhost", "root", "", "logowanie1");

        $mail = $_SESSION['mail'];

        if (isset($_POST['stare_haslo'], $_POST['nowe_haslo'], $_POST['potwierdz_haslo'])) {
            $stare_haslo = $_POST['stare_haslo'];
            $nowe_haslo = $_POST['nowe_haslo'];
            $potwierdz_haslo = $_POST['potwierdz_haslo'];

            $query = "SELECT haslo FROM uzytkownicy WHERE mail = '$mail'";
            $wynik = mysqli_query($conn, $query);
            $w = mysqli_fetch_assoc($wynik);
            
            if (!password_verify($stare_haslo, $w['haslo'])) {
                echo "<p style='color: red;'>Błąd! Stare hasło jest niepoprawne.</p>";
            } 
            elseif (strlen($nowe_haslo) < 8) {
                echo "<p style='color: red;'>Błąd! Hasło ma mniej niż 8 znaków...</p>";
            } 
            elseif ($nowe_haslo !== $potwierdz_haslo) {
                echo "<p style='color: red;'>Błąd! Nowe hasła się nie zgadzają.</p>";
            } 
            else {
                $haslo_h = password_hash($nowe_haslo, PASSWORD_DEFAULT);
                $query = "UPDATE uzytkownicy SET haslo = '$haslo_h' WHERE mail = '$mail'";
                mysqli_query($conn, $query);

                echo "<p style='color: green;'>Pomyślnie zmieniłeś hasło! </p>".'<a href="logout.php">Kliknij tutaj, aby zalogować się ponownie.</a>';
            }
        }
    ?>
</body>
</html>