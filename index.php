<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        * {
            text-align: center;                                                    
        }
    </style>
</head>
    <body>
    <p><a href="index.php">Zaloguj się</a> | <a href="index.php">Zarejestruj się</a></p>

    <form action="index.php" method="POST">
    Podaj email: <input type="text" name="mail" /><br><br>
    Podaj hasło: <input type="text" name="haslo" /><br><br>
    Powtórz hasło: <input type="text" name="haslo2" /><br><br>
    <input type="submit" value="Zarejestruj się">
    </form>

    <?php
        if (isset($_POST['mail'], $_POST['haslo'], $_POST['haslo2'])) {
            $mail = $_POST['mail'];
            $haslo = $_POST['haslo'];
            $haslo2 = $_POST['haslo2'];

            if (!trim($mail) || !trim($haslo) || !trim($haslo2)) {
                echo "<p style='color: red;'>Błąd! Nie wypełniłeś wszystkich pól...</p>";
            }
            else if (!str_contains($mail, "@") || !str_contains($mail, ".")) {
                echo "<p style='color: red;'>Błąd! Mail został podany w złym formacie...</p>";
            }
            else if (strlen($haslo) < 8) {
                echo "<p style='color: red;'>Błąd! Hasło ma mniej niż 8 znaków...</p>";
            }
            else if ($haslo != $haslo2) {
                echo "<p style='color: red;'>Błąd! Hasła różnią się od siebie...</p>";
            }
            else {
                echo "<p style='color: green;'>Pomyślnie zarejestrowano!</p>";
            }
        }
    ?>
</body>
</html>