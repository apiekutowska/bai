<?php
    session_start();
    require "connection.php";
    connection();
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <title>Forum</title>
    </head>

    <body>
        <div id="tytul">Forum</div>
        <?php 
        echo'<div id="wiadomosci">';
            //if(isset($_GET['login']))echo' <a href="wiadomosci.php?login='.$_GET['login'].'">WIADOMOŚCI</a>';
            //else 
                echo' <a href="wiadomosci.php">WIADOMOŚCI</a>';
        echo'</div>';
            if (!isset($_SESSION['login']) && !isset($_SESSION['log'])) 
            { ?>
                <div id="logowanie">
                <form action="zaloguj.php?akcja" method="get">
                    Login: <input type="text" name="login" /><br /><br/>
                    Hasło: <input type="password" name="password" /><br/><br/>
                    <input type="submit" name="akcja" value="Zaloguj się" />
                </form> 
                <br/>Nie masz konta? <a href="rejestracja.php">Zarejestruj się</a></div>
                <?php	
            }
            else  
            {
                 echo '<div id="witaj">Witaj '.$_SESSION['login'];
                 echo '! <a href="logout.php">(Wyloguj się)</a></div>';
            }
        ?>
    </body>
</html>
