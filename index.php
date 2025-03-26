<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magicbook</title>
    <link rel="stylesheet" href="style.css">
</head>
    <body>
        <img src="logo.png" alt="Logo Magicbook">

        <div id="formularz">
            <p><a href="logowanie.php">Zaloguj się</a> | <a href="index.php">Zarejestruj się</a></p>

            <form action="index.php" method="POST">
                Podaj email: <input type="text" name="mail" required /><br><br>
                Podaj hasło: <input type="password" name="haslo" required /><br><br>
                Powtórz hasło: <input type="password" name="haslo2" required /><br><br>
                <input type="submit" value="Zarejestruj się">
            </form>

    <?php
        session_start();

        $conn = new mysqli("localhost", "root", "", "logowanie");
        $id_sesji = session_id();

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

        if (isset($_POST['mail'], $_POST['haslo'], $_POST['haslo2'])) {
            $mail = $_POST['mail'];
            $haslo = $_POST['haslo'];
            $haslo2 = $_POST['haslo2'];

            $haslo_h = password_hash($haslo, PASSWORD_DEFAULT);

            if (!trim($mail) || !trim($haslo) || !trim($haslo2)) {
                echo "<p style='color: red;'>Błąd! Nie wypełniłeś wszystkich pól...</p>";
            }
            else if (!str_contains($mail, "@") || !str_contains($mail, ".")) {
                echo "<p style='color: red;'>Błąd! Mail został podany w złym formacie...</p>";
            }
            else if (znajdz_mail($conn, $mail)) {
                echo "<p style='color: red;'>Błąd! Konto o podanym mailu już istnieje...</p>";
            }
            else if (strlen($haslo) < 8) {
                echo "<p style='color: red;'>Błąd! Hasło ma mniej niż 8 znaków...</p>";
            }
            else if ($haslo != $haslo2) {
                echo "<p style='color: red;'>Błąd! Hasła różnią się od siebie...</p>";
            }
            else {
                mysqli_query($conn, "INSERT INTO uzytkownicy (mail, haslo, id_sesji) VALUES ('$mail', '$haslo_h', '$id_sesji')");
                echo "<p style='color: green;'>Pomyślnie zarejestrowano!</p>";
            }
        }

    ?>
</div>
</body>
</html>