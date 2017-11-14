<?php

  require "connection.php"; //laczenie z baza danych
  connection();
  if(isset($_SESSION['login']))
  {
        echo '<div id="info"><span id="err">Błąd. </span>';
        echo '<a href="index.php">Strona główna</a></div>';
        exit();
  }
?>

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <title>Forum</title>
        <link rel="stylesheet" type="text/css" href="./stylesheet.css" media="screen, projection, tv " />
    </head>
    <body>
    <div id="tytul">Forum</div>
        <div class="tyt">Rejestracja</div>
         <div id="logowanie">
        <form method="get" action="zarejestruj.php?akcja">
            Login: <input type="text" name="login" ><br/><br/>
            Hasło: <input  type="password" name="haslo1"><br/><br/>
            Powtórz hasło: <input  type="password" name="haslo2"><br/><br/>
            <input type="submit" name="akcja" value="Zarejestruj się">
             </form></div>
    </body>
</html>
