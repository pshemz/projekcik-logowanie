<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magicbook</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
    <body>
        <img src="logo.png" alt="Logo Magicbook">

        <div id="formularz">
            <p><a href="logowanie.php">Zaloguj się</a> | <a href="index.php">Zarejestruj się</a></p>

            <form action="logowanie.php" method="POST">
                Podaj email: <input type="text" name="mail" required /><br><br>
                Podaj hasło: <input type="password" name="haslo" required /><br><br>
                <input type="submit" value="Zaloguj się">
            </form>

    <?php
        session_start();

        if (isset($_SESSION['zalogowany']) && $_SESSION['zalogowany'] === true) {
            header('Location: main.php');
            exit;
        }

        $conn = new mysqli("localhost", "root", "", "logowanie");

        function znajdz_mail($conn, $mail) {
            $tab = array();
            $query = "SELECT mail FROM uzytkownicy";
            $wynik = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($wynik) == 0) {
                return false;
            }

            while ($w = mysqli_fetch_assoc($wynik)) {
                $tab[] = $w["mail"];
            }

            foreach ($tab as $spr) {
                if ($spr == $mail) {
                    return true;
                }
            }

            return false;
        }

        function spr_haslo($conn, $mail, $haslo) {
            $query = "SELECT haslo FROM uzytkownicy WHERE mail = '$mail'";
            $wynik = mysqli_query($conn, $query);
            
            if (mysqli_num_rows($wynik) == 0) {
                return false;
            }        

            $w = mysqli_fetch_assoc($wynik);
            $hash = $w['haslo'];

            return password_verify($haslo, $hash);
        }

        if (isset($_POST['mail'], $_POST['haslo'])) {
            $mail = $_POST['mail'];
            $haslo = $_POST['haslo'];

            if (!trim($mail) || !trim($haslo)) {
                echo "<p style='color: red;'>Błąd! Nie wypełniłeś wszystkich pól...</p>";
            }
            else if (!znajdz_mail($conn, $mail)) {
                echo "<p style='color: red;'>Błąd! Konto o podanym mailu nie istnieje...</p>";
            }
            else if (!spr_haslo($conn, $mail, $haslo)) {
                echo "<p style='color: red;'>Błąd! Podałeś niepoprawne hasło...</p>";
            }
            else {
                $_SESSION['zalogowany'] = true;
                $_SESSION['mail'] = $mail;
                header('Location: main.php');
            }
        }

    ?>
</div>
</body>
</html>