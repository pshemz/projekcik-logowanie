<?php
    session_start();

    if (!isset($_SESSION['zalogowany']) || $_SESSION['zalogowany'] !== true) {
        header('Location: logowanie.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magicbook</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <a href="main.php">
        <img src="logo.png" alt="Logo Magicbook" id="logo">
    </a>
        
    <nav id="panel">
        <a href="zmien_haslo.php" class="zmien_haslo">Zmień hasło</a>
        <a href="usun_konto.php" class="usun_konto">Usuń konto</a>
        <a href="logout.php" class="logout">Wyloguj się</a>
    </nav>

    <form action="main.php" method="POST">
        <textarea name="tresc" rows="5" cols="40" placeholder="Co słychać?" required></textarea><br><br>
        <input type="submit" value="Wyślij"></input>
    </form>

    <?php
        $conn = new mysqli("localhost", "root", "", "logowanie1");

        if (isset($_POST['tresc'])) {
            $email = $_SESSION['mail'];

            $query = "SELECT id FROM uzytkownicy WHERE mail = '$email'";
            $wynik = mysqli_query($conn, $query);

            if (mysqli_num_rows($wynik) > 0) {
                $w = mysqli_fetch_assoc($wynik);
                $id_uzytkownika = $w['id'];
            }

            $tresc = mysqli_real_escape_string($conn, $_POST['tresc']);
            $insert = "INSERT INTO posty (id_uzytkownika, tresc, data_posta) VALUES ('$id_uzytkownika', '$tresc', NOW())";

            if (!mysqli_query($conn, $insert)) {
                echo "<p>Błąd przy publikowaniu posta: " . mysqli_error($conn) . "</p>";
            }
        }
    ?>

    <div id="posty">    
        <?php
            $query_p = "SELECT p.tresc, p.data_posta, u.mail FROM posty p JOIN uzytkownicy u ON p.id_uzytkownika = u.id ORDER BY p.data_posta DESC";
            $wynik_p = mysqli_query($conn, $query_p);

            if (mysqli_num_rows($wynik_p) > 0) {
                while ($row = mysqli_fetch_assoc($wynik_p)) {
                    $tresc = $row['tresc'];
                    $data_posta = $row['data_posta'];
                    $mail = $row['mail'];

                    echo "<div class='post'>";
                    echo "<p><strong>$mail</strong> - <em>$data_posta</em></p>";
                    echo "<p>$tresc</p>";
                    echo "</div><hr>";
                }
            }
        ?>
    </div>
</body>
</html>