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
    if(isset($_SESSION['login']))
	{
		echo '<div id="info"><span id="err">Błąd. </span>';
        echo '<a href="index.php">Strona główna</a></div>';
		exit();
	}

    function filtruj($zmienna)
    {
        if(get_magic_quotes_gpc())$zmienna = stripslashes($zmienna); // usuwamy slashe
	    // usuwamy spacje, tagi html oraz niebezpieczne znaki
        return mysql_real_escape_string(htmlspecialchars(trim($zmienna)));
    }
$akcja = $_GET['akcja'];
  if ($akcja == "Zarejestruj się") {
   if (isset($_GET['login'])&&$_GET['login']!="" && isset($_GET['haslo1'])&& $_GET['haslo1']!="" && isset($_GET['haslo2'])&&$_GET['haslo2']!="")
   {
	   $login = filtruj($_GET['login']);
	   $haslo1 = filtruj($_GET['haslo1']);
	   $haslo2 = filtruj($_GET['haslo2']);
	
	   if (mysql_num_rows(mysql_query("SELECT login FROM users WHERE login = '".$login."';")) == 0)
	   {
		  if ($haslo1 == $haslo2) 
		  {
              $ins=@mysql_query("INSERT INTO users ( id, login , haslo) VALUES (NULL,'$login', '$haslo1');") or die('Błąd!');
 		      if($ins)  echo "Konto zostało utworzone! <a href='index.php'>Zaloguj się</a>.";
			  else "Błąd! Spróbuj ponownie później. <a href='index.php'>(Wróć)</a>.";
		  }
		  else echo "<div id=".'info'.">Hasła nie są takie same <a href='rejestracja.php'>(wróć)</a>.</div>";
	   }
	   else echo "<div id=".'info'.">Podany login jest już zajęty <a href='rejestracja.php'>(wróć)</a>.</div>";
    }
    else  echo "<div id=".'info'.">Uzupełnij wszystkie pola. Wróć do <a href='rejestracja.php'>rejestracji</a>.</div>";
  }
?>
    </body>
</html>